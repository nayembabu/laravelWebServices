@extends('layouts.app')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')


@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
    }
    .dep-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 2.5rem;
        background: #ffffff;
        border-radius: 1.5rem;
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    }
    .dep-page-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 2rem;
    }
    .dep-method-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
        padding: 1rem;
        text-align: center;
        background: #f8f9fa;
        display: none;
    }
    .dep-method-card.active {
        display: block;
        animation: depFadeIn 0.6s ease;
    }
    @keyframes depFadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dep-qr {
        max-width: 250px;
        margin: 1.5rem auto;
        border: 8px solid #fff;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    .dep-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: 2px;
    }
    .dep-copy-btn {
        cursor: pointer;
        transition: all 0.2s;
    }
    .dep-history-table thead th {
        background: #495057;
        color: #fff;
    }
    .dep-status-pending { background: #fff3cd; color: #856404; }
    .dep-status-approved { background: #d4edda; color: #155724; }
    .dep-status-rejected { background: #f8d7da; color: #721c24; }
</style>

@endpush



@section('content')

<div class="container-fluid">
    <div class="dep-container">
        <h2 class="text-center dep-page-title"><i class="bi bi-wallet2 me-3 text-primary"></i>ডিপোজিট করুন আপনার ব্যালেন্স (৳{{number_format_bd(auth()->user()->balance());}}/-)</h2>

        <form id="bkash-form" action="{{ route('user.deposite_req') }}" method="POST">
            @csrf
            <!-- Payment Method Selector -->
            <div class="row justify-content-center ">
                <div class="col-md-6">
                    <label for="depMethodSelect" class="form-label fw-semibold">পেমেন্ট মেথড সিলেক্ট করুন</label>
                    <select class="form-select form-select-lg" name="payment_id" id="depMethodSelect">
                        <option value="" selected disabled>একটি মেথড সিলেক্ট করুন</option>
                        @foreach ($payments as $pay)
                            <option value="{{ $pay->id; }}">{{ $pay->name;  }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Deposit Instructions (changes based on selection) -->
            <div id="depMethodDetails">
                <!-- bKash -->
                <div class="dep-method-card">
                    @foreach ($payments as $pay)
                        <div class="all_card" id="{{ $pay->id; }}-card" style="display: none; ">
                            <h4 class=" text-danger fw-bold">{{ $pay->name;  }}</h4>
                            <p class="dep-number" id="bkash-number">{{ $pay->wallet_network;  }}</p>
                            <button class="btn btn-outline-danger dep-copy-btn" data-number="{{ $pay->wallet_network;  }}"><i class="bi bi-copy"></i> কপি করুন</button>
                            <p class="mt-1 "><mark>Note:</mark> {{ $pay->note; }}</p>
                        </div>
                    @endforeach
                    <hr class="my-1">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">অ্যামাউন্ট (টাকা)</label>
                            <input type="number" name="amount" class="form-control form-control-lg" placeholder="যেমন: 1000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ট্রানজেকশন ID</label>
                            <input type="text" class="form-control form-control-lg" name="trx_id" placeholder="ট্রানজেকশন ID লিখুন" required>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger btn-lg px-5">ডিপোজিট রিকোয়েস্ট করুন</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <!-- Deposit History Table -->
        <div class="mt-5">
            <h4 class="mb-4 text-center fw-bold">ডিপোজিট হিস্টরি</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle dep-history-table">
                    <thead>
                        <tr>
                            <th>তারিখ</th>
                            <th>মেথড</th>
                            <th>নাম্বার</th>
                            <th>অ্যামাউন্ট</th>
                            <th>ট্রানজেকশন ID</th>
                            <th>স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recharges as $rc)
                            <tr>
                                <td>{{ $rc->created_at; }}</td>
                                <td>{{ $rc->bank_name; }}</td>
                                <td>{{ $rc->wallet_network; }}</td>
                                <td>{{ $rc->amount; }}</td>
                                <td>{{ $rc->trx_id; }}</td>
                                <td><span class="badge dep-status-{{ $rc->status; }}">{{ Str::ucfirst($rc->status) ; }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')

<script>
    $(document).ready(function() {
        // Method change handler
        $('#depMethodSelect').on('change', function() {
            const method = $(this).val();
            $('.dep-method-card').css('display', 'block');
            $('.all_card').css('display', 'none');
            if (method) {
                $('#' + method + '-card').css('display', 'block');
            }
        });

        // Copy number to clipboard
        $('.dep-copy-btn').on('click', function() {
            const number = $(this).data('number');
            navigator.clipboard.writeText(number);
            alert('নাম্বার কপি হয়েছে: ' + number);
        });

    });
</script>

@endpush









