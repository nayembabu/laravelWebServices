


@extends('layouts.app')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')

@push('styles')
    <style>
        .orders-container {
            max-width: 1400px;
            margin: 1rem auto;
            padding: 1rem;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }
        .orders-page-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        .orders-filter-card {
            background: #f8f9fa;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05);
        }
        .orders-search-input {
            max-width: 400px;
        }
        .orders-table thead th {
            background: #495057;
            color: #fff;
        }
        .orders-status-processing { background: #d1ecf1; color: #856404; }
        .orders-status-pending { background: #fff3cd; color: #856404; }
        .orders-status-rejected { background: #f77f7f; color: #34080b; }
        .orders-status-completed { background: #d4edda; color: #155724; }
        .orders-download-btn.disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
        .orders-pagination .page-link {
            border-radius: 0.5rem;
            margin: 0 0.25rem;
        }
        .orders-pagination .page-item.active .page-link {
            background: #0d6efd;
            border-color: #0d6efd;
        }
        #ordersLoading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,255,255,0.9);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.2);
            z-index: 1000;
            display: none;
        }
    </style>
@endpush


@section('content')
<div class="container-fluid">
    <div id="ordersLoading" style="display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">লোডিং...</span>
        </div>
        <p class="mt-3 mb-0 fw-semibold">ডেটা লোড হচ্ছে...</p>
    </div>

    <div class="orders-container">
        <h2 class="text-center orders-page-title"><i class="bi bi-list-check text-primary"></i>সকল মেক </h2>

        <div class="table-responsive position-relative">
            <table class="table table-striped table-hover align-middle orders-table">
                <thead>
                    <tr>
                        <th>ডাউনলোড</th>
                        <th>নাম</th>
                        <th>NID</th>
                        <th>DOB</th>
                        <th>সময়</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    @foreach ($make as $mk)
                        <tr>
                            <td><a href="{{ route('user.downloadsnid', ['voter_id' => $mk->id]) }}" class="btn btn-success" target="_blank"> <i class="fa fa-download" ></i> </a></td>
                            <td>{{ $mk->nameBangla; }}</td>
                            <td>{{ $mk->nid; }}</td>
                            <td>{{ $mk->dateOfBirth; }}</td>
                            <td>{{ $mk->created_at; }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center orders-pagination" id="ordersPagination"></ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@push('scripts')


@endpush








