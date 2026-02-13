@extends('layouts.app')

@section('title', 'Dashboard - সার্ভিস বাজার')

@push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
        }
        .udash-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .udash-welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
        }
        .udash-stat-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        .udash-stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
        }
        .udash-stat-icon {
            font-size: 3rem;
            opacity: 0.2;
        }
        .udash-quick-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
        }
        .udash-quick-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
        }
        .udash-recent-table thead th {
            background: #495057;
            color: #fff;
        }
        .udash-status-pending { background: #fff3cd; color: #856404; }
        .udash-status-processing { background: #d1ecf1; color: #0c5460; }
        .udash-status-completed { background: #d4edda; color: #155724; }
    </style>
@endpush

@section('content')

    <div class="udash-container">
        <!-- Welcome Section -->
        <div class="udash-welcome-card mb-5">
            <h1 class="display-5 fw-bold mb-3">স্বাগতম, Vireta!</h1>
            <p class="lead">আপনার সার্ভিস ড্যাশবোর্ডে স্বাগতম। এখান থেকে সবকিছু ম্যানেজ করুন।</p>
            <div class="mt-4">
                <span class="fs-4">বর্তমান ব্যালেন্স: </span>
                <span class="display-6 fw-bold">৳{{number_format_bd(auth()->user()->balance());}}/-</span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card udash-stat-card text-center p-4 bg-primary text-white">
                    <i class="bi bi-cart4 udash-stat-icon"></i>
                    <h2 class="display-6 fw-bold mb-0">{{ count(auth()->user()->serviceOrders) }}</h2>
                    <p class="mb-0">মোট অর্ডার</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card udash-stat-card text-center p-4 bg-warning text-white">
                    <i class="bi bi-hourglass-split udash-stat-icon"></i>
                    <h2 class="display-6 fw-bold mb-0">{{ auth()->user()->serviceOrders->where('status', 'pending')->count() }}</h2>
                    <p class="mb-0">পেন্ডিং</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card udash-stat-card text-center p-4 bg-success text-white">
                    <i class="bi bi-check2-all udash-stat-icon"></i>
                    <h2 class="display-6 fw-bold mb-0">{{ auth()->user()->serviceOrders->where('status', 'approved')->count() }}</h2>
                    <p class="mb-0">কমপ্লিটেড</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card udash-stat-card text-center p-4 bg-danger text-white">
                    <i class="bi bi-clock udash-stat-icon"></i>
                    <h2 class="display-6 fw-bold mb-0">{{ auth()->user()->serviceOrders->where('status', 'rejected')->count() }}</h2>
                    <p class="mb-0">বাতিল</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h4 class="mb-4 fw-bold text-center">কুইক অ্যাকশন</h4>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="{{ route('user.services') }}" class="card udash-quick-card text-decoration-none text-center p-5 bg-light">
                    <i class="bi bi-cart-plus fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold text-dark">নতুন সার্ভিস অর্ডার</h5>
                    <p class="text-muted">নতুন সার্ভিস অর্ডার করুন</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('user.deposite') }}" class="card udash-quick-card text-decoration-none text-center p-5 bg-light">
                    <i class="bi bi-wallet2 fs-1 text-success mb-3"></i>
                    <h5 class="fw-bold text-dark">ডিপোজিট করুন</h5>
                    <p class="text-muted">ব্যালেন্স যোগ করুন</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('user.downloads') }}" class="card udash-quick-card text-decoration-none text-center p-5 bg-light">
                    <i class="bi bi-list-check fs-1 text-info mb-3"></i>
                    <h5 class="fw-bold text-dark">সকল অর্ডার দেখুন</h5>
                    <p class="text-muted">আপনার অর্ডার হিস্টরি</p>
                </a>
            </div>
        </div>

        <!-- Recent Orders -->
        <h4 class="mb-4 fw-bold text-center">সাম্প্রতিক অর্ডারসমূহ</h4>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle udash-recent-table">
                        <thead>
                            <tr>
                                <th>সার্ভিস</th>
                                <th>অ্যামাউন্ট</th>
                                <th>স্ট্যাটাস</th>
                                <th>তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lastOrders as $ser)
                                <tr>
                                    <td>{{ $ser->order_details; }}</td>
                                    <td>{{ $ser->amount; }}</td>
                                    <td><span class="badge udash-status-{{ $ser->status; }}">{{ $ser->status; }}</span></td>
                                    <td>{{ $ser->created_at; }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <a href="{{ route('user.downloads') }}" class="btn btn-outline-primary">সকল অর্ডার দেখুন →</a>
                </div>
            </div>
        </div>
    </div>
@endsection






