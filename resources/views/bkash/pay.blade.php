@extends('layouts.app')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')


@push('styles')
  <style>
    :root {
      --bkash-pink: #E2136E;
      --bkash-dark: #C0105E;
      --light-bg: #f8f9fa;
    }
    .btn-bkash {
      background-color: var(--bkash-pink);
      border-color: var(--bkash-pink);
      color: white;
    }
    .btn-bkash:hover {
      background-color: var(--bkash-dark);
      border-color: var(--bkash-dark);
    }
    .card-header-bkash {
      background-color: var(--bkash-pink);
      color: white;
    }
    .amount-input {
      font-size: 2.2rem;
      font-weight: bold;
      text-align: center;
    }
    body {
      background-color: var(--light-bg);
      padding-bottom: 3rem;
    }
    .payment-card {
      max-width: 480px;
      margin: 2rem auto;
      box-shadow: 0 8px 25px rgba(226, 19, 110, 0.15);
    }
    .history-card {
      max-width: 900px;
      margin: 2rem auto;
    }
    .btn-manual-deposit {
      background: linear-gradient(135deg, #E2136E 0%, #C0105E 100%);
      border: none;
      color: white;
      border-radius: 999px;
      box-shadow: 0 6px 20px rgba(226, 19, 110, 0.35);
      transition: all 0.3s ease;
      padding: 14px 50px;
      font-size: 1.3rem;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
    }
    .btn-manual-deposit:hover {
      background: linear-gradient(135deg, #C0105E 0%, #E2136E 100%);
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(226, 19, 110, 0.45);
      color: white;
    }
  </style>

@endpush



@section('content')

  <!-- Header -->
  <header class="bg-dark text-white text-center py-3 mb-4">
    <h1 class="mb-0">bKash Payment Gateway</h1>
    <p class="lead mb-0">নিরাপদ ও দ্রুত পেমেন্ট</p>

    <a href="{{ route('user.deposite') }}" class="btn-manual-deposit">
        Manual Payment
    </a>

  </header>

  <div class="container">

        {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>❌ Error!</strong> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>✅ Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>❌ Failed!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Payment Form Card -->
    <div class="card payment-card border-0 rounded-4 overflow-hidden">
      <div class="card-header card-header-bkash text-center py-4">
        <h4 class="mb-0">পেমেন্ট করুন</h4>
      </div>

      <div class="card-body p-4 p-md-5">
        <form id="paymentForm"  action="{{ route('pay.start') }}" method="POST">
            @csrf
          <!-- CSRF token যদি backend framework ব্যবহার করেন তাহলে যোগ করবেন -->
          <!-- <input type="hidden" name="_token" value="..."> -->

          <div class="mb-4 text-center">
            <label for="amount" class="form-label fs-5 fw-bold">টাকার পরিমাণ (৳)</label>
            <input
              type="number"
              class="form-control amount-input border-primary shadow-sm"
              id="amount"
              name="amount"
              min="1"
              step="1"
              placeholder="0.00"
              required
              autofocus>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-bkash btn-lg py-3 fw-bold fs-5 rounded-pill">
              bKash-এ পে করুন →
            </button>
          </div>

          <div class="text-center mt-4 small text-muted">
            নিরাপদ পেমেন্ট • কোনো ফি নেই (৳৫০০ পর্যন্ত) • তাৎক্ষণিক
          </div>
        </form>
      </div>
    </div>

    <!-- Previous Payments History -->
    <div class="card history-card border-0 shadow-sm rounded-4 mt-5">
      <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">আগের পেমেন্টসমূহ</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>তারিখ</th>
                        <th>ইনভয়েস</th>
                        <th>পরিমাণ</th>
                        <th>স্ট্যাটাস</th>
                        <th>ট্রানজেকশন আইডি</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($orders as $order)
                    @php
                        $payment = $order->payments->first(); // latest payment দরকার হলে latest() ব্যবহার করবেন
                    @endphp

                    <tr class="text-center">
                        <td>
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </td>

                        <td>
                            <strong>{{ $order->invoice }}</strong>
                        </td>

                        <td>
                            ৳ {{ number_format($order->amount, 2) }}
                        </td>

                        <td>
                            @if($order->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($order->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge bg-secondary">Cancelled</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>

                        <td>
                            @if($payment && $payment->trx_id)
                                <span class="text-success fw-bold">
                                    {{ $payment->trx_id }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            কোন অর্ডার পাওয়া যায়নি।
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
      </div>
      <div class="card-footer text-center bg-white border-0">
        <small class="text-muted">মোট পেমেন্ট: ৳ ৫,০০০+</small>
      </div>
    </div>

  </div>

  @endsection


  @push('scripts')
