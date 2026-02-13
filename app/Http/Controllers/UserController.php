<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;


use App\Models\Service;
use App\Models\UserServiceOrder;
use App\Models\UserBalanceAdd;
use App\Models\UserBalanceCut;
use App\Models\PaymentMethod;
use App\Models\UserRecharge;

class UserController extends Controller
{
    public function dashboard()
    {
        $lastOrders = auth()->user()->serviceOrders()
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();
        return view('user.dashboard', compact('lastOrders'));
    }

    public function view_all_services()
    {
        $services = Service::all();
        return view('user.services_page', compact('services'));
    }

    public function order_services(Request $request)
    {
        // Validate input
        $request->validate([
            'service' => 'required|exists:services,id',
            'order_details' => 'required|min:5'
        ]);

        $service = Service::findOrFail($request->service);
        $userId  = Auth::id();

        // Calculate balance
        $totalAdd = UserBalanceAdd::where('user_id', $userId)->sum('amount');
        $totalCut = UserBalanceCut::where('user_id', $userId)->sum('amount');
        $currentBalance = $totalAdd - $totalCut;

        // Balance check
        if ($currentBalance < $service->rate) {
            return back()->with('error', 'আপনার ব্যালেন্স পর্যাপ্ত নয়। অর্ডার করা সম্ভব নয়।');
        }

        DB::beginTransaction();

        try {

            // ✅ Place order (store to variable)
            $order = UserServiceOrder::create([
                'user_id'       => $userId,
                'service_id'    => $service->id,
                'order_details' => $request->order_details,
                'amount'        => $service->rate,
                'status'        => 'pending',
            ]);

            // ✅ Cut balance
            UserBalanceCut::create([
                'user_id' => $userId,
                'amount'  => $service->rate,
                'reason'  => 'service_order',
                'note'    => 'Service Order ID: ' . $order->id,
            ]);

            DB::commit();

            return back()->with('success', 'সার্ভিস অর্ডার সফল হয়েছে ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'কিছু সমস্যা হয়েছে, আবার চেষ্টা করুন ❌');
        }
    }

    public function download_order_file()
    {
        $orders = UserServiceOrder::with('service')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('user.download_view_file', compact('orders'));
    }

    public function ordersAjax(Request $request)
    {

        $userId = auth()->id();

        $query = UserServiceOrder::with('service')->where('user_id', $userId)->orderBy('created_at', 'desc');

        // যদি min/max date থাকে
        if ($request->minDate) {
            $query->whereDate('created_at', '>=', $request->minDate);
        }
        if ($request->maxDate) {
            $query->whereDate('created_at', '<=', $request->maxDate);
        }

        // যদি কোন ডেট না আসে, ডিফল্টে আজকের অর্ডার
        if (!$request->minDate && !$request->maxDate) {
            $query->whereDate('created_at', Carbon::today());
        }

        // গ্লোবাল সার্চ
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_details', 'like', "%$search%")
                ->orWhereHas('service', function($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        // Pagination
        $perPage = 100;
        $page = $request->page ?? 1;
        $orders = $query->orderBy('created_at', 'desc')
                        ->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        $totalItems = $query->count();
        $totalPages = ceil($totalItems / $perPage);

        // JSON response
        $data = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'type' => $order->service->name ?? 'নির্দিষ্ট নেই',
                'info' => $order->order_details,
                'status' => $order->status,
                'downloadable' => $order->status === 'completed',
                'rate' => '৳'.number_format($order->amount),
                'time' => $order->created_at->format('Y-m-d H:i')
            ];
        });

        return response()->json([
            'data' => $data,
            'totalPages' => $totalPages
        ]);

    }

    public function deposite_user_accounts()
    {
        $payments = PaymentMethod::all();
        $recharges = UserRecharge::with('paymentmethod')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();
        return view('user.deposite_page', compact('payments', 'recharges'));
    }


    public function deposite_request(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:1',
            'trx_id' => 'required|string|max:255',
        ]);

        try {

            $recharge = UserRecharge::create([
                'user_id' => auth()->id(),
                'payment_id' => $request->payment_id,
                'amount' => $request->amount,
                'trx_id' => $request->trx_id,
                'status' => 'pending', // default
            ]);

            // 🔔 Telegram Notification
            $message = "💰 New Deposit Request\n\n"
            . "👤 User: ".auth()->user()->name."\n"
            . "💵 Amount: ".$request->amount."\n"
            . "🧾 TRX ID: ".$request->trx_id."\n"
            . "📅 Time: ".$recharge->created_at->format('d M Y h:i A');

            // ✅ multiple admin send
            foreach (config('services.telegram.chat_ids') as $chatId) {
                Http::post(
                    "https://api.telegram.org/bot".config('services.telegram.token')."/sendMessage",
                    [
                        'chat_id' => $chatId,
                        'text' => $message,
                    ]
                );
            }

            return back()->with('success', 'ডিপোজিট রিকোয়েস্ট সফলভাবে সাবমিট হয়েছে ✅ ১০ মিনিট অপেক্ষা করুন। ');

        } catch (\Exception $e) {

            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function view_user_profile()
    {
        return view('user.profile_page');
    }

    public function update_user_profile(Request $request)
    {
        $user = auth()->user();

        // ✅ Validation
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'     => 'required|string|max:20',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        // ✅ Base data
        $data = [
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
        ];

        // ✅ যদি password দেয়া থাকে
        if ($request->filled('password')) {
            $data['password']       = Hash::make($request->password);
            $data['show_password']  = $request->password;
        }

        // ✅ Update
        $user->update($data);

        return back()->with('success', 'প্রোফাইল সফলভাবে আপডেট হয়েছে ✅');
    }



}

