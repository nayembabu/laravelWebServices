<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        return view('admin.dashboard');
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


}


