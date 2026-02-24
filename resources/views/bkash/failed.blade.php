<!doctype html>
<html>
<head><meta charset="utf-8"><title>Payment Failed</title></head>
<body>
    <h2>❌ Payment Failed / Cancelled</h2>

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <p>Invoice: {{ $order->invoice }}</p>
    <p>Amount: {{ $order->amount }} BDT</p>
    <p>Status: {{ $order->status }}</p>

    <a href="{{ route('pay.form') }}">Try Again</a>
</body>
</html>
