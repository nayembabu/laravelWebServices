
@extends('layouts.adminapp')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')

@push('styles')
    <style>
        .orderlist-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }
        .orderlist-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        .orderlist-table thead th {
            background: #495057;
            color: #fff;
        }
        .orderlist-status-pending { background: #fff3cd; color: #856404; }
        .orderlist-status-processing { background: #d1ecf1; color: #0c5460; }
        .orderlist-status-completed { background: #d4edda; color: #155724; }
        .orderlist-download-btn.disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
    </style>
@endpush

@section('content')
    <h2 class="text-center orderlist-title"><i class="bi bi-list-check me-3 text-primary"></i> অর্ডার লিস্ট </h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle orderlist-table">
            <thead>
                <tr>
                    <th>অ্যাকশন</th>
                    <th>অর্ডারে ধরন</th>
                    <th>তথ্য</th>
                    <th>রেট</th>
                    <th>সময়</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted">আরও অর্ডার থাকলে পেজিনেশন যোগ করা যাবে। এখানে স্যাম্পল ডেটা দেখানো হয়েছে।</p>
    </div>

@endsection

@push('scripts')

<script>

    function loadLastOrders() {
        $.ajax({
            url: "{{ route('admin.order_waiting_see') }}",
            method: "GET",
            dataType: "json",
            success: function(res){
                let html = '';
                if(res.length === 0){
                    html = '<tr><td colspan="5" class="text-center text-muted py-3">কোনো অর্ডার পাওয়া যায়নি।</td></tr>';
                } else {
                    res.forEach(function(order){
                        html += `
                            <tr>
                                <td><button class="btn btn-info btn-sm add-admin-btn " data-id="${order.id}"><i class="bi bi-cart"></i> Add</button></td>
                                <td class="fw-semibold">${order.type}</td>
                                <td>${order.info}</td>
                                <td class="fw-semibold text-primary">${order.rate}</td>
                                <td>${order.time}</td>
                            </tr>
                        `;
                    });
                }
                $('.orderlist-table tbody').html(html);
            },
            error: function(err){
                console.error("AJAX Error:", err);
            }
        });
    }

    $(document).ready(function(){
        // প্রথমে লোড
        loadLastOrders();

        // প্রতি 3 মিনিটে রিফ্রেশ (180000ms)
        setInterval(loadLastOrders, 180000);



        // ✅ Add button click
        $(document).on('click', '.add-admin-btn', function(){
            let orderId = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.assign_admin_order') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: orderId
                },
                success: function(res){
                    if(res.success){
                        alert('অর্ডার অ্যাডমিনে অ্যাসাইন করা হয়েছে ✅');
                        loadLastOrders(); // আপডেট দেখানোর জন্য আবার লোড
                    } else {
                        alert('কিছু সমস্যা হয়েছে ❌');
                    }
                },
                error: function(err){
                    console.error(err);
                    alert('AJAX ত্রুটি ঘটেছে ❌');
                }
            });
        });
    });
















    // <tr>
    //     <td><button class="btn btn-info btn-sm "><i class="bi bi-cart"></i> Add </button></td>
    //     <td class="fw-semibold">ওয়েব ডেভেলপমেন্ট</td>
    //     <td>লগইন সিস্টেম + রেসপন্সিভ ডিজাইন Laravel দিয়ে</td>
    //     <td class="fw-semibold text-primary">$1,200</td>
    //     <td>2026-02-10 14:30</td>
    // </tr>
</script>


@endpush




