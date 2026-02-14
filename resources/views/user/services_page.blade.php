@extends('layouts.app')

@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')

@push('styles')

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
        }
        .svc-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }
        .svc-page-title {
            font-weight: 700;
            color: #2c3e50;
        }
        .svc-service-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 0.35rem 1rem rgba(0, 0, 0, 0.08);
            background: #fff;
        }
        .svc-service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }
        .svc-service-card .card-body {
            padding: 1rem;
        }
        .svc-service-card label {
            cursor: pointer;
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0.5rem 0;
        }
        .svc-service-card .form-check-input {
            margin-right: 1rem;
            width: 1em;
            height: 1.4em;
        }
        .svc-service-icon {
            font-size: 2rem;
            margin-right: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .svc-service-card input:checked + label .svc-service-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        #svcOrderDetails {
            display: none;
            margin-top: 2rem;
            animation: svcFadeIn 0.5s ease;
        }
        @keyframes svcFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #svcOrdersTable .table {
            border-radius: 1rem;
            overflow: hidden;
        }
        .svc-remove-btn {
            transition: all 0.2s;
        }
        .svc-remove-btn:hover {
            background-color: #dc3545 !important;
        }
        .svc-download-btn {
            cursor: not-allowed;
        }
        .service-radio:checked + .service-content::after {
            content: "   ✔✔✔";
            color: #0d6efd;
            font-weight: bold;
        }
    </style>

@endpush

@section('content')

<div class="container-fluid">
    <div class="svc-container">
        <h2 class="text-center svc-page-title"><i class="bi bi-cart4 me-3 text-primary"></i>সার্ভিস অর্ডার করুন, আপনার ব্যালেন্স (৳{{number_format_bd(auth()->user()->balance());}}/-) </h2>

        <!-- Available Services List - Two Columns -->
        <form action="{{ route('user.addservice') }}" method="post" >
            @csrf
            <div class="row g-4 mb-1">
                <div class="col-lg-12">
                    <div class="card svc-service-card h-100">
                        <div class="card-body row">
                            @foreach ($services as $ser)
                                <div class=" service-box d-flex flex-column gap-1 col-md-5 col-5 " style="border: 1px solid red; margin: 5px; border-radius: 8px; " >
                                    <label class="d-flex align-items-center ml-2 ">
                                        <input class="form-check-input service-radio " required type="radio" name="service" value="{{$ser->id}}">
                                        <span class="fw-semibold ml-2 service-content "> {{$ser->name}} ({{number_format($ser->rate, 0)}}tk)</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details Section -->
            <div id="svcOrderDetails" class="card border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="bi bi-file-text me-2"></i>অর্ডারের বিস্তারিত লিখুন</h5>
                    <div class="mb-3">
                        <label for="svcDetails" class="form-label fw-semibold">আপনার প্রয়োজনীয়তা / বিবরণ</label>
                        <textarea  class="form-control form-control-lg" required name="order_details" id="svcDetails" rows="6" placeholder="এখাবে বিস্তারিত লিখুন। 123456789 - আক্কাস"></textarea>
                    </div>
                    <button type="submit" id="svcAddOrder" class="btn btn-success btn-lg px-5"><i class="bi bi-plus-circle me-2"></i>অর্ডারে যোগ করুন</button>
                </div>
            </div>
        </form>

        <!-- Ordered Services Table -->
        <div id="svcOrdersTable" class="card mt-5 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 text-white "><i class="bi bi-check2-all me-2"></i>আপনার অর্ডার লিস্ট</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>অর্ডারে ধরন</th>
                                <th>তথ্য</th>
                                <th>নোট</th>
                                <th>রিসিভ</th>
                                <th>ডাউনলোড</th>
                                <th>রেট</th>
                                <th>সময়</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($querys as $sng)

                                @php
                                    // 🎨 Status badge class
                                    $statusClass = match($sng->status) {
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        'pending' => 'bg-warning text-dark',
                                        default => 'bg-secondary'
                                    };

                                    // 📂 File url
                                    $fileUrl = $sng->admin_file ? asset('storage/'.$sng->admin_file) : null;
                                @endphp

                                <tr>
                                    <td>{{ $sng->name }}</td>
                                    <td>{{ $sng->order_details }}</td>
                                    <td>{{ $sng->admin_note ?? 'N/A' }}</td>

                                    {{-- ✅ Status Badge --}}
                                    <td>
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($sng->status) }}
                                        </span>
                                    </td>

                                    {{-- ✅ Download Button Logic --}}
                                    <td>
                                        @if($sng->status === 'approved' && $fileUrl)
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-success btn-sm">
                                                <i class="bi bi-download"></i>
                                            </a>

                                        @elseif($sng->status === 'pending')
                                            <button class="btn btn-warning btn-sm" disabled>
                                                Processing
                                            </button>

                                        @elseif($sng->status === 'rejected')
                                            <button class="btn btn-danger btn-sm" disabled>
                                                Rejected
                                            </button>

                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                N/A
                                            </button>
                                        @endif
                                    </td>

                                    <td>{{ $sng->amount }}</td>
                                    <td>{{ $sng->created_at->format('d M Y, h:i A') }}</td>
                                </tr>

                            @endforeach
                        </tbody>

                    </table>
                    <p class="text-muted text-center" id="svcEmptyMessage"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    $(document).ready(function() {

        // Show details section when a service is selected
        $('input[name="service"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#svcOrderDetails').slideDown();
                $('#svcEmptyMessage').hide();
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "Nice!",
                text: "{{ session('success') }}",
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session('error') }}"
            });
        @endif
        @if(session('warning'))
            Swal.fire({
                icon: "warning",
                title: "Something Wrong",
                text: "{{ session('warning') }}"
            });
        @endif

    });
</script>

@endpush
