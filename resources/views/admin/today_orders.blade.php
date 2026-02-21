@extends('layouts.adminapp')
@section('title', 'আজকের অর্ডার - সার্ভিস বাজার')

@push('styles')
<style>
    .badge-status {
        padding: 0.4rem 0.7rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <h3 class="mb-4 text-center"><i class="bi bi-list-check me-2"></i> আজকের অর্ডার</h3>

    <div class="table-responsive">
        <table class="table table-hover align-middle" id="today-orders-table">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Phone</th>
                    <th>Order Amount</th>
                    <th>Balance Before</th>
                    <th>Balance After</th>
                    <th>Status</th>
                    <th>Ordered At</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                <tr><td colspan="8" class="text-center">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    const tableBody = $('#orders-table-body');

    function loadOrders() {
        $.ajax({
            url: "{{ route('admin.today_orders_list') }}",
            method: 'GET',
            success: function(orders) {
                tableBody.empty();
                if(orders.length === 0) {
                    tableBody.append('<tr><td colspan="8" class="text-center text-muted">No orders today</td></tr>');
                    return;
                }

                orders.forEach(function(order, index){
                    const statusBadge = order.status === 'completed' ?
                        '<span class="badge bg-success badge-status">Completed</span>' :
                        (order.status === 'pending' ?
                            '<span class="badge bg-warning text-dark badge-status">Pending</span>' :
                            '<span class="badge bg-danger badge-status">Rejected</span>'
                        );

                    tableBody.append(`
                        <tr>
                            <td>${index+1}</td>
                            <td>${order.user_name}</td>
                            <td>${order.user_phone}</td>
                            <td>$${parseFloat(order.order_amount).toFixed(2)}</td>
                            <td>$${parseFloat(order.before_balance).toFixed(2)}</td>
                            <td>$${parseFloat(order.after_balance).toFixed(2)}</td>
                            <td>${statusBadge}</td>
                            <td>${order.created_at}</td>
                        </tr>
                    `);
                });
            },
            error: function() {
                tableBody.html('<tr><td colspan="8" class="text-center text-danger">Failed to load orders.</td></tr>');
            }
        });
    }

    loadOrders();
});
</script>
@endpush
