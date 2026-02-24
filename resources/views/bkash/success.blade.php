<!doctype html>
<html>
<head><meta charset="utf-8"><title>Payment Success</title></head>
<body>
    <h2>✅ Payment Successful</h2>
    <p>Invoice: {{ $order->invoice }}</p>
    <p>Amount: {{ $order->amount }} BDT</p>
    <p>Status: {{ $order->status }}</p>
</body>
</html>
