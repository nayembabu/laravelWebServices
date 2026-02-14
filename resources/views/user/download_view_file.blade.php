
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
        <h2 class="text-center orders-page-title"><i class="bi bi-list-check text-primary"></i>সকল অর্ডারস</h2>

        <div class="orders-filter-card">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="ordersMinDate" class="form-label fw-semibold">শুরুর তারিখ</label>
                    <input type="text" class="form-control dates" id="ordersMinDate" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="ordersMaxDate" class="form-label fw-semibold">শেষের তারিখ</label>
                    <input type="text" class="form-control dates" id="ordersMaxDate" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <button id="ordersApplyFilter" class="btn btn-primary w-100"><i class="bi bi-funnel me-2"></i>ফিল্টার করুন</button>
                </div>
            </div>
        </div>

        <div class="table-responsive position-relative">
            <table class="table table-striped table-hover align-middle orders-table">
                <thead>
                    <tr>
                        <th>অর্ডারে ধরন</th>
                        <th>তথ্য</th>
                        <th>রিসিভ</th>
                        <th>ডাউনলোড</th>
                        <th>রেট</th>
                        <th>সময়</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    <!-- AJAX দিয়ে এখানে রো যোগ হবে -->
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
<script>
    const storageUrl = "{{ asset('storage') }}";
function fetchOrders({ search = '', minDate = '', maxDate = '', page = 1 }) {
    $('#ordersLoading').show();

    $.ajax({
        url: "{{ route('user.orders.ajax') }}",
        method: "GET",
        data: { search, minDate, maxDate, page },
        success: function(res){
            let html = '';
            if(res.data.length === 0){
                html = '<tr><td colspan="7" class="text-center text-muted py-4">কোনো অর্ডার পাওয়া যায়নি।</td></tr>';
            } else {
                res.data.forEach(item => {
                    const statusClass = item.status === 'approved' ? 'orders-status-completed' :
                                        item.status === 'rejected' ? 'orders-status-rejected' :
                                        item.status === 'pending' ? 'orders-status-processing' :
                                        'orders-status-pending';

                    const downloadBtn =
                            item.status === 'rejected'
                                ? item.note ? `<span class="badge bg-danger">রিজেক্ট কারণ: ${item.note}</span>` : `<span class="badge bg-danger">রিজেক্ট হয়েছে</span>`
                                : (item.downloadable && item.file
                                    ? `<a class="btn btn-success btn-sm orders-download-btn" href="${storageUrl}/${item.file}" target="_blank"><i class="bi bi-download"></i></a>`
                                    : '<button class="btn btn-info btn-sm orders-download-btn disabled" disabled><i class="bi bi-download"></i></button>');


                    html += `<tr>
                                <td class="fw-semibold">${item.type}</td>
                                <td>${item.info.replace(/\n/g,'<br>')}</td>
                                <td><span class="badge ${statusClass}">${item.status}</span></td>
                                <td>${downloadBtn}</td>
                                <td class="fw-semibold text-primary">${item.rate}</td>
                                <td>${item.time}</td>
                             </tr>`;
                });
            }
            $('#ordersBody').html(html);

            // Pagination
            let paginationHtml = '';
            const totalPages = res.totalPages;
            if(totalPages > 1){
                paginationHtml += `<li class="page-item ${page === 1 ? 'disabled':''}">
                                    <a class="page-link" href="#" data-page="${page-1}">পূর্ববর্তী</a></li>`;
                for(let i=1;i<=totalPages;i++){
                    paginationHtml += `<li class="page-item ${i===page?'active':''}">
                                       <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                paginationHtml += `<li class="page-item ${page===totalPages?'disabled':''}">
                                   <a class="page-link" href="#" data-page="${page+1}">পরবর্তী</a></li>`;
            }
            $('#ordersPagination').html(paginationHtml);

            $('#ordersLoading').hide();
        }
    });
}

$(document).ready(function(){
    // প্রথম লোডে আজকের অর্ডার
    fetchOrders({ minDate:'{{ date('Y-m-d') }}', maxDate:'{{ date('Y-m-d') }}' });

    $('#ordersApplyFilter').click(function(){
        fetchOrders({
            search: $('#ordersGlobalSearch').val(),
            minDate: $('#ordersMinDate').val(),
            maxDate: $('#ordersMaxDate').val(),
            page: 1
        });
    });

    // Pagination
    $('#ordersPagination').on('click','a.page-link', function(e){
        e.preventDefault();
        const page = $(this).data('page');
        if(!$(this).parent().hasClass('disabled')){
            fetchOrders({
                search: $('#ordersGlobalSearch').val(),
                minDate: $('#ordersMinDate').val(),
                maxDate: $('#ordersMaxDate').val(),
                page: page
            });
        }
    });
});
</script>
@endpush


