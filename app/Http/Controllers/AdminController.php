<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Service;
use App\Models\UserServiceOrder;
use App\Models\UserBalanceAdd;
use App\Models\UserBalanceCut;
use App\Models\PaymentMethod;
use App\Models\UserRecharge;
use App\Models\Order;
use App\Models\Payment;


class AdminController extends Controller
{
    public function __construct()
    {

            if (!auth()->check()) {
                return redirect()->route('login');
            }

            // ❌ User trying to access admin
            if (auth()->user()->role !== 'admin') {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('home')
                    ->with('error', 'Permission denied');
            }

    }

    public function dashboard()
    {

        $totalUsers = User::count();

        $totalOrders = UserServiceOrder::count();
        $todayOrders = UserServiceOrder::whereDate('created_at', Carbon::today())->count();

        $totalRechargeAmount = Order::where('status', 'paid')
                            ->whereDate('created_at', Carbon::today())
                            ->sum('amount');
        $pendingDeposits = UserRecharge::where('status', 'pending')
                                        ->whereNull('approved_at')
                                        ->count();

        $pendingOrders = UserServiceOrder::where('status', 'pending')->count();
        return view('admin.dashboard', compact('totalUsers', 'totalOrders', 'pendingDeposits', 'pendingOrders', 'totalRechargeAmount', 'todayOrders'));
    }

    public function all_waiting_orders()
    {
        return view('admin.waiting_order_list_file');
    }

    public function all_waiting_order_see()
    {
        $orders = UserServiceOrder::with('service')
            ->where('check_admin_id', NULL)
            ->where('admin_assign_time', NULL)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $data = $orders->map(function($order){
            return [
                'id'    => $order->id,
                'type'  => $order->service->name ?? 'নির্দিষ্ট নেই',
                'info'  => $order->order_details,
                'rate'  => '৳'.number_format($order->amount),
                'status'=> $order->status,
                'time'  => $order->created_at->format('Y-m-d H:i')
            ];
        });

        return response()->json($data);
    }

    public function assignAdminOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:user_service_orders,id'
        ]);

        try {
            $order = UserServiceOrder::findOrFail($request->order_id);

            $order->update([
                'check_admin_id' => auth()->id(),
                'admin_assign_time' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function all_waiting_mybox_order_see()
    {
        return view('admin.waiting_mybox_order_list_file');
    }

    public function mybox_orders()
    {
        $orders = UserServiceOrder::with('service')
            ->where('check_admin_id', auth()->id())
            ->where('delivary_time', NULL)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $orders->map(function($order){
            return [
                'id'        => $order->id,
                'type'      => $order->service->name ?? 'নির্দিষ্ট নেই',
                'info'      => $order->order_details,
                'rate'      => '৳'.number_format($order->amount),
                'status'    => $order->status,
                'ordertime' => $order->created_at->format('Y-m-d H:i:s'),
                'taketime' => $order->admin_assign_time ? $order->admin_assign_time : 'Not Assigned',
            ];
        });
        return response()->json($data);
    }

    public function submitAdminOrder(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'order_id'      => 'required|exists:user_service_orders,id',
            'submitted_file'=> 'required|file|max:10240',
            'message'       => 'nullable|string'
        ]);

        try {

            // 🔎 Order খুঁজে বের করা
            $order = UserServiceOrder::findOrFail($request->order_id);

            // 📂 File Upload
            $file = $request->file('submitted_file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // 🔹 public/storage/admin_orders এ সরাসরি আপলোড
            $destinationPath = public_path('storage/admin_orders');

            // ফাইল move করা
            $file->move($destinationPath, $fileName);

            // 🔹 ডাটাবেজে path রাখতে চাইলে
            $path = 'admin_orders/' . $fileName;



            // 📝 Order Update
            $order->update([
                'admin_file' => $path,
                'admin_note' => $request->message,
                'status' => 'approved',
                'admin_id' => Auth::id(),
                'delivary_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order submitted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rejectAdminOrder(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'order_id' => 'required|exists:user_service_orders,id',
            'reason' => 'required|string|max:1000'
        ]);

        DB::beginTransaction();

        try {

            // 🔎 Order find
            $order = UserServiceOrder::lockForUpdate()->findOrFail($request->order_id);

            // ❌ Already rejected হলে stop
            if ($order->status === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already rejected'
                ], 400);
            }

            // 💰 Refund amount
            $refundAmount = $order->amount;

            // 📝 Order update
            $order->update([
                'status' => 'rejected',
                'admin_note' => $request->reason,
                'check_admin_id' => Auth::id(),
                'delivary_time' => now(),
            ]);

            // 💳 Balance add log
            UserBalanceAdd::create([
                'user_id' => $order->user_id,
                'amount' => $refundAmount,
                'source' => 'order_refund',
                'note' => 'Order #' . $order->id . ' rejected refund',
            ]);

            // 👉 যদি তোমার users table এ balance column থাকে
            // তাহলে এটা enable করো 👇
            // DB::table('users')
            //     ->where('id', $order->user_id)
            //     ->increment('balance', $refundAmount);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order rejected & amount refunded'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleServiceOrder(Request $request)
    {
        $current = setting('service_order_enabled',1);

        DB::table('settings')
            ->where('key','service_order_enabled')
            ->update(['value' => !$current]);

        return back()->with('success','Service ordering status updated');
    }

    public function deposit_list()
    {
        return view('admin.deposit_list_file');
    }

    public function pendingDeposits()
    {
        $deposits = UserRecharge::with('user')
            ->where('status','pending')
            ->whereNull('approved_at')
            ->get();

        return response()->json($deposits);
    }

    public function update_deposit_status(Request $request)
    {
        $request->validate([
            'deposit_id' => 'required|exists:user_recharges,id',
            'type' => 'required|in:approve,reject'
        ]);

        try {
            $deposit = UserRecharge::findOrFail($request->deposit_id);

            if ($request->type === 'approve') {

                DB::beginTransaction();

                $deposit->update([
                    'status' => 'approved',
                    'approved_at' => Carbon::now(),
                    'approved_by' => auth()->id(),
                ]);

                // Balance add log
                UserBalanceAdd::create([
                    'user_id' => $deposit->user_id,
                    'amount' => $deposit->amount,
                    'source' => 'deposit',
                    'note' => 'Deposit approved, trx ID: ' . $deposit->trx_id,
                ]);

                // 👉 যদি তোমার users table এ balance column থাকে
                // তাহলে এটা enable করো 👇
                // DB::table('users')
                //     ->where('id', $deposit->user_id)
                //     ->increment('balance', $deposit->amount);
                DB::commit();

            } else {
                $deposit->update([
                    'status' => 'rejected',
                    'approved_at' => Carbon::now(),
                    'approved_by' => auth()->id(),
                ]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function user_list()
    {
        return view('admin.user_list_file');
    }

    public function adminServicesList()
    {
        return view('admin.services_list');
    }

    public function servicesList()
    {
        return response()->json(Service::latest()->get());
    }

    public function serviceUpdateRate(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->rate = $request->rate;
        $service->save();

        return response()->json(['success'=>true]);
    }

    public function serviceStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate'        => 'required|numeric|min:0',
            'status'      => 'required|in:active,inactive',
        ]);

        Service::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'rate'=>$request->rate,
            'status'=>$request->status
        ]);

        return response()->json(['success'=>true]);
    }

    public function serviceToggleStatus(Request $request)
    {
        $service = Service::findOrFail($request->id);

        $service->status = $service->status === 'active' ? 'inactive' : 'active';
        $service->save();

        return response()->json([
            'success'=>true,
            'status'=>$service->status
        ]);
    }

    public function list()
    {
        $users = User::latest()->get()->map(function($user){

            $credit = UserBalanceAdd::where('user_id', $user->id)->sum('amount');
            $debit  = UserBalanceCut::where('user_id', $user->id)->sum('amount');

            $user->balance = $credit - $debit;

            return $user;
        });

        return response()->json($users);
    }

    // Get single user details
    public function show($id)
    {
        $user = User::findOrFail($id);

        $credit = UserBalanceAdd::where('user_id', $id)->sum('amount');
        $debit  = UserBalanceCut::where('user_id', $id)->sum('amount');

        $user->balance = $credit - $debit;

        return response()->json($user);
    }

    // Add balance
    public function addBalance(Request $request)
    {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'amount'=>'required|numeric|min:1'
        ]);

        UserBalanceAdd::create([
            'user_id'=>$request->user_id,
            'amount'=>$request->amount,
            'source'=>'admin', // optional
            'note'=>'Added via admin panel'
        ]);

        $newBalance = UserBalanceAdd::where('user_id',$request->user_id)->sum('amount')
                    - UserBalanceCut::where('user_id',$request->user_id)->sum('amount');

        return response()->json(['new_balance'=>$newBalance]);
    }

    // Deduct balance
    public function deductBalance(Request $request)
    {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'amount'=>'required|numeric|min:1'
        ]);

        UserBalanceCut::create([
            'user_id'=>$request->user_id,
            'amount'=>$request->amount,
            'reason'=>'admin_deduction', // optional
            'note'=>'Deducted via admin panel'
        ]);

        $newBalance = UserBalanceAdd::where('user_id',$request->user_id)->sum('amount')
                    - UserBalanceCut::where('user_id',$request->user_id)->sum('amount');

        return response()->json(['new_balance'=>$newBalance]);
    }

    // Change user password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'new_password'=>'required|string|min:6'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->password = Hash::make($request->new_password);
        $user->show_password = $request->new_password;
        $user->save();

        return response()->json(['success'=>true]);
    }












    public function todayOrders()
    {
        return view('admin.today_orders');
    }


    public function todayOrdersList()
    {
        $today = Carbon::today();

        $orders = UserServiceOrder::with('user')
            ->whereDate('created_at', $today)
            ->orderBy('created_at','asc')
            ->get();

        $data = $orders->map(function($order) {
            $user = $order->user;

            // সঠিক ব্যালেন্স হিসাব
            // অর্ডারের আগে সব Add/Cut
            $beforeBalance = UserBalanceAdd::where('user_id',$user->id)
                                ->where('created_at','<',$order->created_at)
                                ->sum('amount')
                           - UserBalanceCut::where('user_id',$user->id)
                                ->where('created_at','<',$order->created_at)
                                ->sum('amount');

            $afterBalance = $beforeBalance - $order->amount; // order amount deducted

            return [
                'id' => $order->id,
                'user_name' => $user->name,
                'user_phone' => $user->phone ?? 'N/A',
                'order_amount' => $order->amount,
                'before_balance' => $beforeBalance,
                'after_balance' => $afterBalance,
                'status' => $order->status,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json($data);
    }









}


