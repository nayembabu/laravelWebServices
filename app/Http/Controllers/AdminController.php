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

        $pendingDeposits = UserRecharge::where('status', 'pending')
                                        ->whereNull('approved_at')
                                        ->count();

        $pendingOrders = UserServiceOrder::where('status', 'pending')->count();
        return view('admin.dashboard', compact('totalUsers', 'totalOrders', 'pendingDeposits', 'pendingOrders'));
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

            $path = $file->storeAs(
                'admin_orders',
                $fileName,
                'public'
            );

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


}


