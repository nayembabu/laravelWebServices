

@extends('layouts.adminapp')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')

@push('styles')

@endpush

@section('content')

    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white py-4">
                <h3 class="mb-0 text-center">
                    <i class="bi bi-people me-3"></i>
                    All Users
                </h3>
                <p class="mb-0 text-center mt-2 opacity-75">Manage users: view details, adjust balance, change password</p>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-muted small fw-semibold">#</th>
                                <th class="text-muted small fw-semibold">User</th>
                                <th class="text-muted small fw-semibold">Email</th>
                                <th class="text-muted small fw-semibold">Balance</th>
                                <th class="text-muted small fw-semibold">Joined</th>
                                <th class="text-muted small fw-semibold">Status</th>
                                <th class="text-center text-muted small fw-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            <!-- Example User 1 -->
                            <tr data-user-id="1001">
                                <td class="fw-medium">1</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                                            <i class="bi bi-person-fill text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">user123</span>
                                            <small class="text-muted d-block">ID: 1001</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">user123@example.com</td>
                                <td class="fw-bold text-success balance-cell">$1,250.00</td>
                                <td class="text-muted small">2025-12-10</td>
                                <td>
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Active</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm rounded-pill shadow-sm view-user-btn">
                                        <i class="bi bi-eye me-1"></i> Manage
                                    </button>
                                </td>
                            </tr>

                            <!-- Example User 2 -->
                            <tr data-user-id="1002">
                                <td class="fw-medium">2</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                                            <i class="bi bi-person-fill text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">john_doe</span>
                                            <small class="text-muted d-block">ID: 1002</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">john@example.com</td>
                                <td class="fw-bold text-success balance-cell">$480.50</td>
                                <td class="text-muted small">2026-01-05</td>
                                <td>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm rounded-pill shadow-sm view-user-btn">
                                        <i class="bi bi-eye me-1"></i> Manage
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="no-users-message" class="text-center py-5 text-muted" style="display: none;">
                    <i class="bi bi-person-x display-1 text-muted d-block mb-3"></i>
                    <h5>No users found</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Large User Management Modal -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-gradient bg-primary text-white">
                    <h4 class="modal-title">
                        <i class="bi bi-person-gear me-2"></i> Manage User
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-5">
                    <div class="row g-5">
                        <!-- Left: Avatar & Basic Info -->
                        <div class="col-lg-4 text-center">
                            <img id="modal-avatar" src="https://via.placeholder.com/200?text=User" class="rounded-circle shadow-lg" style="width:200px;height:200px;object-fit:cover;" alt="Avatar">
                            <h5 class="mt-4 mb-1" id="modal-username"></h5>
                            <p class="text-muted mb-2">User ID: <span id="modal-userid"></span></p>
                            <span id="modal-status-badge" class="badge px-4 py-2 rounded-pill fs-6"></span>
                        </div>

                        <!-- Right: Details & Actions -->
                        <div class="col-lg-8">
                            <!-- Basic Details -->
                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="bg-light rounded-3 p-4 shadow-sm">
                                        <p class="text-muted small mb-1">Email</p>
                                        <h6 id="modal-email" class="fw-semibold"></h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light rounded-3 p-4 shadow-sm">
                                        <p class="text-muted small mb-1">Phone</p>
                                        <h6 id="modal-phone" class="fw-semibold"></h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light rounded-3 p-4 shadow-sm">
                                        <p class="text-muted small mb-1">Current Balance</p>
                                        <h5 id="modal-balance" class="fw-bold text-success"></h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light rounded-3 p-4 shadow-sm">
                                        <p class="text-muted small mb-1">Joined Date</p>
                                        <h6 id="modal-joined" class="fw-semibold"></h6>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Sections -->
                            <hr class="my-5">

                            <!-- Add / Deduct Balance -->
                            <h5 class="mb-4"><i class="bi bi-wallet2 text-success me-2"></i> Adjust Balance</h5>
                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-success"><i class="bi bi-plus-circle me-2"></i>Add Balance</h6>
                                            <form id="add-balance-form">
                                                <input type="hidden" id="user-id-add" name="user_id">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="amount" placeholder="Amount" min="0.01" step="0.01" required>
                                                    <button type="submit" class="btn btn-success rounded-end">
                                                        <i class="bi bi-plus-lg"></i> Add
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-danger"><i class="bi bi-dash-circle me-2"></i>Deduct Balance</h6>
                                            <form id="deduct-balance-form">
                                                <input type="hidden" id="user-id-deduct" name="user_id">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="amount" placeholder="Amount" min="0.01" step="0.01" required>
                                                    <button type="submit" class="btn btn-danger rounded-end">
                                                        <i class="bi bi-dash-lg"></i> Deduct
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <h5 class="mb-4"><i class="bi bi-key text-warning me-2"></i> Change Password</h5>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <form id="change-password-form">
                                        <input type="hidden" id="user-id-password" name="user_id">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <input type="password" class="form-control" name="new_password" placeholder="New Password" required minlength="6">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required minlength="6">
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-warning rounded-pill px-5">
                                                    <i class="bi bi-shield-lock me-2"></i> Change Password
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let currentUserId = null;
            let currentRow = null;

            // Open Modal & Fill Data
            $('.view-user-btn').on('click', function () {
                currentRow = $(this).closest('tr');
                currentUserId = currentRow.data('user-id');

                // Fill modal data (ডাটা তোমার ব্যাকএন্ড থেকে AJAX দিয়ে লোড করতে পারো, এখানে example)
                $('#modal-avatar').attr('src', 'https://via.placeholder.com/200?text=' + currentRow.find('.fw-semibold').text());
                $('#modal-username').text(currentRow.find('.fw-semibold').text());
                $('#modal-userid').text(currentUserId);
                $('#modal-email').text(currentRow.find('td').eq(2).text());
                $('#modal-phone').text('Not provided'); // database থেকে নিবে
                $('#modal-balance').text(currentRow.find('.balance-cell').text());
                $('#modal-joined').text(currentRow.find('td').eq(4).text());

                // Status badge
                let statusText = currentRow.find('.badge').text().trim();
                let badge = $('#modal-status-badge');
                badge.removeClass('bg-success bg-warning bg-danger text-dark');
                if (statusText === 'Active') badge.addClass('bg-success').text('Active');
                else if (statusText === 'Pending') badge.addClass('bg-warning text-dark').text('Pending');
                else badge.addClass('bg-danger').text('Inactive');

                // Set user_id in forms
                $('#user-id-add, #user-id-deduct, #user-id-password').val(currentUserId);

                $('#userDetailsModal').modal('show');
            });

            // Add Balance
            $('#add-balance-form').on('submit', function (e) {
                e.preventDefault();
                let amount = $(this).find('input[name="amount"]').val();
                if (!amount || amount <= 0) return;

                Swal.fire({
                    title: 'Confirm Add Balance',
                    text: `Add $${amount} to this user?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Add'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/user/add-balance', // তোমার রুট
                            method: 'POST',
                            data: { user_id: currentUserId, amount: amount },
                            success: function (response) {
                                let newBalance = parseFloat(response.new_balance || 0);
                                $('#modal-balance').text('$' + newBalance.toFixed(2));
                                currentRow.find('.balance-cell').text('$' + newBalance.toFixed(2));

                                Swal.fire('Success!', 'Balance added successfully.', 'success');
                                $(this)[0].reset();
                            },
                            error: function () {
                                Swal.fire('Error!', 'Failed to add balance.', 'error');
                            }
                        });
                    }
                });
            });

            // Deduct Balance
            $('#deduct-balance-form').on('submit', function (e) {
                e.preventDefault();
                let amount = $(this).find('input[name="amount"]').val();
                if (!amount || amount <= 0) return;

                Swal.fire({
                    title: 'Confirm Deduct Balance',
                    text: `Deduct $${amount} from this user?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Deduct',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/user/deduct-balance', // তোমার রুট
                            method: 'POST',
                            data: { user_id: currentUserId, amount: amount },
                            success: function (response) {
                                let newBalance = parseFloat(response.new_balance || 0);
                                $('#modal-balance').text('$' + newBalance.toFixed(2));
                                currentRow.find('.balance-cell').text('$' + newBalance.toFixed(2));

                                Swal.fire('Success!', 'Balance deducted successfully.', 'success');
                                $(this)[0].reset();
                            },
                            error: function () {
                                Swal.fire('Error!', 'Failed to deduct balance (insufficient?).', 'error');
                            }
                        });
                    }
                });
            });

            // Change Password
            $('#change-password-form').on('submit', function (e) {
                e.preventDefault();
                let newPass = $(this).find('input[name="new_password"]').val();
                let confirmPass = $(this).find('input[name="confirm_password"]').val();

                if (newPass !== confirmPass) {
                    Swal.fire('Error!', 'Passwords do not match!', 'error');
                    return;
                }
                if (newPass.length < 6) {
                    Swal.fire('Error!', 'Password must be at least 6 characters.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Confirm Password Change',
                    text: 'Change password for this user?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Change'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/user/change-password', // তোমার রুট
                            method: 'POST',
                            data: { user_id: currentUserId, new_password: newPass },
                            success: function () {
                                Swal.fire('Success!', 'Password changed successfully.', 'success');
                                $(this)[0].reset();
                            },
                            error: function () {
                                Swal.fire('Error!', 'Failed to change password.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush


