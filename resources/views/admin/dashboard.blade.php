
@extends('layouts.adminapp')

@section('title', 'Admin Dashboard - সার্ভিস বাজার')

@push('styles')
    <style>
        .admin-stat-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .admin-stat-card:hover {
            transform: translateY(-8px);
        }
        .admin-chart-placeholder {
            height: 350px;
            background: #f8f9fa;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
    </style>
    <style>
        .card {
            border-radius: 12px;
        }
        .card:hover {
            transform: translateY(-3px);
            transition: 0.3s ease;
        }
    </style>
@endpush

@section('content')

        <h2 class="mb-4 fw-bold">অ্যাডমিন ড্যাশবোর্ড ওভারভিউ  </h2>

        <div class="container mt-4">
            <div class="row g-4">

                <!-- Server Copy Token Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">সার্ভার কপির টোকেন</h6>

                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="fw-bold text-primary mb-0 tocken_assign "></h4>

                                <button class="btn btn-sm btn-outline-primary" id="refreshToken">
                                    🔄 রিফ্রেশ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today Recharge Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">আজকের মোট রিচার্জ</h6>

                            <h4 class="fw-bold text-success mb-0">
                                ৳ {{ number_format($totalRechargeAmount ?? 0, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">মোট অর্ডার</h6>

                            <h4 class="fw-bold text-dark mb-0">
                                {{ $todayOrders ?? 0 }}
                            </h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <form action="{{ route('admin.setting.toggle.service') }}" method="POST">
            @csrf
            @php
                $enabled = setting('service_order_enabled',1);
            @endphp
            <button class="btn {{ $enabled ? 'btn-success' : 'btn-danger' }}">
                {{ $enabled ? 'Service ON' : 'Service OFF' }}
            </button>
        </form>

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-sm-6">
                <div class="card admin-stat-card text-center p-4 bg-primary text-white">
                    <i class="bi bi-people fs-1 mb-3"></i>
                    <h3 class="fw-bold mb-0">{{ number_format($totalUsers) }}</h3>
                    <p class="mb-0">টোটাল ইউজার</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card admin-stat-card text-center p-4 bg-success text-white">
                    <i class="bi bi-cart4 fs-1 mb-3"></i>
                    <h3 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h3>
                    <p class="mb-0">টোটাল অর্ডার</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card admin-stat-card text-center p-4 bg-info text-white">
                    <i class="bi bi-hourglass-split fs-1 mb-3"></i>
                    <h3 class="fw-bold mb-0">{{ number_format($pendingDeposits) }}</h3>
                    <p class="mb-0">পেন্ডিং ডিপোজিট</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card admin-stat-card text-center p-4 bg-warning text-white">
                    <i class="bi bi-bag fs-1 mb-3"></i>
                    <h3 class="fw-bold mb-0">{{ number_format($pendingOrders) }}</h3>
                    <p class="mb-0">পেন্ডিং অর্ডার</p>
                </div>
            </div>
        </div>

        <!-- Charts & Recent Activity -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">রেভেনিউ ওভারভিউ (লাস্ট ৩০ দিন)</h5>
                        <div class="admin-chart-placeholder">
                            <p class="mb-0">Chart.js দিয়ে চার্ট যোগ করুন (Placeholder)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">সাম্প্রতিক অ্যাকটিভিটি</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">নতুন ইউজার রেজিস্টার করেছে (Vireta)</li>
                            <li class="list-group-item">অর্ডার #1005 কমপ্লিট হয়েছে</li>
                            <li class="list-group-item">ডিপোজিট রিকোয়েস্ট অ্যাপ্রুভ করা হয়েছে</li>
                            <li class="list-group-item">নতুন সার্ভিস অর্ডার হয়েছে</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')

<script>function getTokenByApi() {
    $('.tocken_assign').text('Loading...');

    $.get("{{ route('servercopy.token') }}", function(res){
        if(res.ok){
            $('.tocken_assign').text(res.balance);
        }else{
            $('.tocken_assign').text('0');
            alert(res.message || 'Failed');
        }
    }).fail(function(){
        $('.tocken_assign').text('Error');
        alert('Server error');
    });
}

$('#refreshToken').click(function(){
    getTokenByApi();
});

// page load এ auto load চাইলে
$(document).ready(function(){
    getTokenByApi();
});
</script>

@endpush


