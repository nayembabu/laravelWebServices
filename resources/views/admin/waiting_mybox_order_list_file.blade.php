
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
                    <th>অবস্থা</th>
                    <th>তোলা সময়</th>
                    <th>চেক সময়</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted">আরও অর্ডার থাকলে পেজিনেশন যোগ করা যাবে। এখানে স্যাম্পল ডেটা দেখানো হয়েছে।</p>
    </div>










    <!-- Submit (View) Modal -->
    <div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="submitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitModalLabel">
                        অর্ডার সাবমিট করুন — <span id="submit-order-info"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div >
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="submit-order-id">

                        <div class="mb-3">
                            <label for="fileUpload" class="form-label fw-bold">ফাইল আপলোড করুন</label>
                            <input class="form-control" type="file" id="fileUpload" name="submitted_file" required>
                        </div>

                        <div class="mb-3">
                            <label for="additionalText" class="form-label fw-bold">অতিরিক্ত টেক্সট / বার্তা (ঐচ্ছিক)</label>
                            <textarea class="form-control" id="additionalText" rows="5" name="message"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                        <button type="button" class="btn btn-success submitOrders" >সাবমিট করুন</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">
                        অর্ডার রিজেক্ট করুন — <span id="reject-order-info"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div >
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="reject-order-id">
                        <div class="mb-3">
                            <label for="rejectReason" class="form-label fw-bold">রিজেক্টের কারণ লিখুন <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejectReason" rows="5" name="reason" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                        <button type="button" class="btn btn-danger rejectOrder" >রিজেক্ট করুন</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script>

    function loadLastOrders() {
        $.ajax({
            url: "{{ route('admin.mybox_order_waiting_get') }}",
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
                                <td>
                                    <button class="btn btn-primary btn-sm view-btn" data-bs-toggle="modal" data-bs-target="#submitModal" data-order-id="${order.id}" data-order-type="${order.type}"> <i class="bi bi-check-circle"></i> </button>
                                    <button class="btn btn-danger btn-sm reject-btn" data-bs-toggle="modal" data-bs-target="#rejectModal" data-order-id="${order.id}" data-order-type="${order.type}"><i class="bi bi-x-circle"></i></button>
                                </td>
                                <td class="fw-semibold">${order.type}</td>
                                <td>${order.info}</td>
                                <td class="fw-semibold text-primary">${order.rate}</td>
                                <td>${order.status}</td>
                                <td>${order.ordertime}</td>
                                <td>${order.taketime}</td>
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

        $(document).on('click', '.submitOrders', function () {
            submitOrder();
        });

        $(document).on('click', '.rejectOrder', function () {
            rejectOrder();
        });


        function submitOrder() {
            let formData = new FormData();
            formData.append('order_id', $('#submit-order-id').val());
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('message', $('#additionalText').val());
            formData.append('submitted_file', $('#fileUpload')[0].files[0]);

            $.ajax({
                url: "{{ route('admin.submit_order') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res){
                    $('#submitModal').modal('hide');
                    loadLastOrders();
                    toastr.success("অর্ডার সফলভাবে সাবমিট করা হয়েছে!");
                },
                error: function(err){
                    console.error("AJAX Error:", err);
                    toastr.error("অর্ডার সাবমিট করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।");
                }
            });
        }

        function rejectOrder() {
            let orderId = $('#reject-order-id').val();
            let reason = $('#rejectReason').val();

            $.ajax({
                url: "{{ route('admin.reject_order') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: orderId,
                    reason: reason
                },
                success: function(res){
                    $('#rejectModal').modal('hide');
                    loadLastOrders();
                    toastr.success("অর্ডার সফলভাবে রিজেক্ট করা হয়েছে!");
                },
                error: function(err){
                    console.error("AJAX Error:", err);
                    toastr.error("অর্ডার রিজেক্ট করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।");
                }
            });
        }

         // View Modal এ ডেটা সেট করা
         $('#submitModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var orderId = button.data('order-id');
            var orderType = button.data('order-type');

            $('#submit-order-id').val(orderId);
            $('#submit-order-info').text(orderType + " অর্ডার");
        });

        // Reject Modal এ ডেটা সেট করা
        $('#rejectModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var orderId = button.data('order-id');
            var orderType = button.data('order-type');

            $('#reject-order-id').val(orderId);
            $('#reject-order-info').text(orderType + " অর্ডার");
        });



    });


</script>


@endpush




