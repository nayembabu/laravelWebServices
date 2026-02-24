<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\UserBalanceAdd;
use App\Services\BkashService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BkashPaymentController extends Controller
{
    public function showForm()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->latest()
                        ->get();
        return view('bkash/pay', compact('orders'));
    }

    public function startPayment(Request $request, BkashService $bkash)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $amount = (float) $validated['amount'];
        $invoice = 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

        // 1) Order insert (pending)
        $order = Order::create([
            'invoice' => $invoice,
            'amount'  => $amount,
            'status'  => 'pending',
            'user_id' => Auth::id(),
        ]);

        try {
            // 2) Create payment in bKash
            $create = $bkash->createPayment($amount, $invoice);

            // 3) Payment log insert
            $payment = Payment::create([
                'order_id'        => $order->id,
                'user_id'         => Auth::id(),
                'gateway'         => 'bkash',
                'payment_id'      => $create['paymentID'] ?? null,
                'amount'          => $amount,
                'status'          => isset($create['bkashURL']) ? 'initiated' : 'failed',
                'create_response' => $create,
                'error_message'   => isset($create['bkashURL']) ? null : ($create['errorMessage'] ?? 'Create payment failed'),
            ]);

            // 4) Redirect to bKash page
            if (!empty($create['bkashURL'])) {
                return redirect()->away($create['bkashURL']);
            }

            // create failed -> mark order failed
            $order->update(['status' => 'failed']);
            return redirect()
                ->route('pay.form')
                ->with('error', 'Payment initiation failed. Please try again.');

        } catch (Throwable $e) {
            $order->update(['status' => 'failed']);

            Payment::create([
                'order_id'      => $order->id,
                'user_id'       => Auth::id(),
                'gateway'       => 'bkash',
                'amount'        => $amount,
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('pay.form')
                ->with('error', 'Payment initiation failed. Please try again.');
        }
    }

    public function callback(Request $request, BkashService $bkash)
    {
        // bKash callback সাধারণত query param এ দেয়: status, paymentID
        $status = $request->query('status');
        $paymentId = $request->query('paymentID');

        // paymentID না থাকলে fail
        if (!$paymentId) {
            return redirect()
                ->route('pay.form')
                ->with('error', 'Invalid payment callback.');
        }

        // আমাদের DB payment খুঁজে বের করি
        $payment = Payment::where('payment_id', $paymentId)->latest()->first();
        if (!$payment) {
            return redirect()
                ->route('pay.form')
                ->with('error', 'Payment not found.');
        }

        $order = $payment->order;

           // Cancel or failed from bkash page
        if ($status !== 'success') {

            $payment->update([
                'status' => $status === 'cancel' ? 'cancelled' : 'failed',
            ]);

            $order->update([
                'status' => $status === 'cancel' ? 'cancelled' : 'failed',
            ]);

            return redirect()
                ->route('pay.form')
                ->with('error', 'Payment ' . ucfirst($status ?? 'failed'));
        }

        // success হলে execute call
        try {
            $execute = $bkash->executePayment($paymentId);

            // bKash successful execute হলে সাধারণত trxID থাকে
            $isSuccess = !empty($execute['trxID']) && (($execute['transactionStatus'] ?? '') !== 'Cancelled');

            $payment->update([
                'trx_id'           => $execute['trxID'] ?? null,
                'status'           => $isSuccess ? 'success' : 'failed',
                'execute_response' => $execute,
                'error_message'    => $isSuccess ? null : ($execute['errorMessage'] ?? 'Execute failed'),
            ]);

            $order->update([
                'status' => $isSuccess ? 'paid' : 'failed',
            ]);

            if ($isSuccess) {

                // 💳 Balance add log
                UserBalanceAdd::create([
                    'user_id' => $order->user_id,
                    'amount' => $execute['amount'],
                    'source' => 'bkash_recharge',
                    'note' => 'Auto #' . $execute['trxID'] . '-Payment',
                ]);

                return redirect()
                    ->route('pay.form')
                    ->with('success', 'Payment Successful! TrxID: ' . $execute['trxID']);
            }

            return redirect()
                ->route('pay.form')
                ->with('error', 'Payment execution failed.');

        } catch (Throwable $e) {
            $payment->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $order->update(['status' => 'failed']);

            return redirect()
                ->route('pay.form')
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function success(string $invoice)
    {
        $order = Order::where('invoice', $invoice)->firstOrFail();
        return view('bkash/pay', compact('order'));
    }

    public function failed(string $invoice)
    {
        $order = Order::where('invoice', $invoice)->firstOrFail();
        return view('bkash/pay', compact('order'));
    }
}
