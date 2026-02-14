

@extends('layouts.adminapp')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')

@push('styles')

@endpush

@section('content')

    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Pending Deposits</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="deposit-table-body"></tbody>
                    </table>
                </div>

                <!-- যদি কোনো pending deposit না থাকে -->
                <div id="no-deposits-message" class="text-center py-5 text-muted" style="display: none;">
                    No pending deposits found.
                </div>
            </div>
        </div>
    </div>



@endsection

@push('scripts')

    <script>
        // jQuery AJAX for Approve & Reject
        $(document).ready(function () {
            // CSRF Token (Laravel এর জন্য, অন্য ফ্রেমওয়ার্কে দরকার না হলে কমেন্ট আউট করো)
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadPendingDeposits();
            function loadPendingDeposits() {
                $.ajax({
                    url: "{{ route('admin.deposit.pending') }}",
                    method: "GET",
                    dataType: "json",
                    success: function(res){
                        let html = '';
                        if(res.length === 0){
                            $('#no-deposits-message').show();
                            $('#deposit-table-body').html('');
                        } else {
                            $('#no-deposits-message').hide();

                            res.forEach(function(dep, index){
                                html += `
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${dep.user.name}</td>
                                    <td>${dep.amount}</td>
                                    <td>${dep.trx_id}</td>
                                    <td>${dep.created_at}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark status-badge">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm approve-btn" data-id="${dep.id}">
                                            Approve
                                        </button>
                                        <button class="btn btn-danger btn-sm reject-btn" data-id="${dep.id}">
                                            Reject
                                        </button>
                                    </td>
                                </tr>`;
                            });

                            $('#deposit-table-body').html(html);

                            // Re-bind click events after table load
                            // bindApproveReject();
                        }
                    }
                });
            }


            // Approve Button
            $(document).on('click', '.approve-btn', function () {
                let depositId = $(this).data('id');
                let row = $(this).closest('tr');

                if (confirm('Are you sure you want to APPROVE this deposit?')) {
                    $.ajax({
                        url: "{{ route('admin.deposit.update_status') }}",
                        method: 'POST',
                        data: {
                            deposit_id: depositId,
                            type: 'approve',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            loadPendingDeposits();
                            row.find('.status-badge')
                            .removeClass('bg-warning text-dark')
                            .addClass('bg-success')
                            .text('Approved');

                            // অথবা পুরো রো রিমুভ করো (pending list থেকে সরিয়ে ফেলতে)
                            row.fadeOut(500, function () {
                                $(this).remove();
                                checkEmptyTable();
                            });

                            alert('Deposit approved successfully!');
                        },
                        error: function (xhr) {
                            let msg = xhr.responseJSON?.message || 'Something went wrong!';
                            alert('Error: ' + msg);
                        }
                    });
                }
            });

            // Reject Button
            $(document).on('click', '.reject-btn', function () {
                let depositId = $(this).data('id');
                let row = $(this).closest('tr');

                if (confirm('Are you sure you want to REJECT this deposit?')) {
                    $.ajax({
                        url: "{{ route('admin.deposit.update_status') }}",
                        method: 'POST',
                        data: {
                            deposit_id: depositId,
                            type: 'reject',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            row.find('.status-badge')
                            .removeClass('bg-warning text-dark')
                            .addClass('bg-danger')
                            .text('Rejected');
                            loadPendingDeposits();

                            // অথবা রো রিমুভ
                            row.fadeOut(500, function () {
                                $(this).remove();
                                checkEmptyTable();
                            });

                            alert('Deposit rejected successfully!');
                        },
                        error: function (xhr) {
                            let msg = xhr.responseJSON?.message || 'Something went wrong!';
                            alert('Error: ' + msg);
                        }
                    });
                }
            });

            // টেবিল খালি হলে message দেখাও
            function checkEmptyTable() {
                if ($('#deposit-table-body tr').length === 0) {
                    $('#no-deposits-message').show();
                }
            }
        });
    </script>
@endpush






