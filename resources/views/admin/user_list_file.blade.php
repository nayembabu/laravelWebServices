@extends('layouts.adminapp')
@section('title','User Management')

@section('content')

    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white py-4">
                <h3 class="mb-0 text-center">
                    <i class="bi bi-people me-2"></i> User Management
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Balance</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body"></tbody>
                    </table>
                </div>

                <div id="no-users-message" class="text-center py-5 text-muted" style="display:none;">
                    <h5>No users found</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- USER MODAL -->
    <div class="modal fade" id="userDetailsModal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Manage User</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">

                        <div class="col-lg-4 text-center">
                            <img src="{{ asset('img/user/' . rand(0,102) . '.png') }}"
                                class="rounded-circle mb-3" width="150">
                            <h5 id="modal-username"></h5>
                            <p class="text-muted">ID: <span id="modal-userid"></span></p>
                            <span id="modal-status-badge" class="badge"></span>
                        </div>

                        <div class="col-lg-8">

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label>Email</label>
                                    <p id="modal-email"></p>
                                </div>
                                <div class="col-md-6">
                                    <label>Phone</label>
                                    <p id="modal-phone"></p>
                                </div>
                                <div class="col-md-6">
                                    <label>Balance</label>
                                    <h5 class="text-success" id="modal-balance"></h5>
                                </div>
                                <div class="col-md-6">
                                    <label>Joined</label>
                                    <p id="modal-joined"></p>
                                </div>
                            </div>

                            <hr>

                            <!-- ADD BALANCE -->
                            <h6>Add Balance</h6>
                            <form id="add-balance-form" class="mb-3">
                                <input type="hidden" id="user-id-add">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="amount" placeholder="Amount">
                                    <button class="btn btn-success">Add</button>
                                </div>
                            </form>

                            <!-- DEDUCT -->
                            <h6>Deduct Balance</h6>
                            <form id="deduct-balance-form" class="mb-3">
                                <input type="hidden" id="user-id-deduct">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="amount" placeholder="Amount">
                                    <button class="btn btn-danger">Deduct</button>
                                </div>
                            </form>

                            <hr>

                            <!-- PASSWORD -->
                            <h6>Change Password</h6>
                            <form id="change-password-form">
                                <input type="hidden" id="user-id-password">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <input type="password" class="form-control" name="new_password" placeholder="New Password">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-warning w-100">Change</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script>

        let currentUserId = null;
        let currentRow = null;

        // LOAD USERS
        function loadUsers(){
            $.get("{{ route('admin.users.list') }}", function(users){

                let html = '';

                if(users.length === 0){
                    $('#no-users-message').show();
                    return;
                }

                users.forEach((user,index)=>{

                    html += `
                    <tr data-user-id="${user.id}">
                        <td>${index+1}</td>
                        <td><strong>${user.username ?? user.name}</strong></td>
                        <td>${user.email}</td>
                        <td class="balance-cell">$${parseFloat(user.balance ?? 0).toFixed(2)}</td>
                        <td>${user.created_at ?? '-'}</td>
                        <td>
                            <span class="badge ${user.status === 'active' ? 'bg-success':'bg-danger'}">
                                ${user.status}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm view-user-btn">Manage</button>
                        </td>
                    </tr>
                    `;
                });

                $('#users-table-body').html(html);
            });
        }

        loadUsers();


        // OPEN MODAL
        $(document).on('click','.view-user-btn',function(){

            currentRow = $(this).closest('tr');
            currentUserId = currentRow.data('user-id');

            $.get(`{{ route('admin.users.show', ':id') }}`.replace(':id', currentUserId), function(user){

                $('#modal-username').text(user.username ?? user.name);
                $('#modal-userid').text(user.id);
                $('#modal-email').text(user.email);
                $('#modal-phone').text(user.phone ?? 'N/A');
                $('#modal-balance').text('$'+parseFloat(user.balance ?? 0).toFixed(2));
                $('#modal-joined').text(user.created_at);

                $('#modal-status-badge')
                    .removeClass('bg-success bg-danger')
                    .addClass(user.status === 'active' ? 'bg-success':'bg-danger')
                    .text(user.status);

                $('#user-id-add,#user-id-deduct,#user-id-password').val(user.id);

                $('#userDetailsModal').modal('show');
            });
        });


        // ADD BALANCE
        $('#add-balance-form').submit(function(e){
            e.preventDefault();

            $.post("{{ route('admin.users.update_balance_add') }}",{
                user_id: currentUserId,
                amount: $(this).find('input[name="amount"]').val(),
                _token:"{{ csrf_token() }}"
            },function(res){

                $('#modal-balance').text('$'+parseFloat(res.new_balance).toFixed(2));
                currentRow.find('.balance-cell').text('$'+parseFloat(res.new_balance).toFixed(2));

                Swal.fire('Success','Balance added','success');
            });
        });


        // DEDUCT
        $('#deduct-balance-form').submit(function(e){
            e.preventDefault();

            $.post("{{ route('admin.users.update_balance_cut') }}",{
                user_id: currentUserId,
                amount: $(this).find('input[name="amount"]').val(),
                _token:"{{ csrf_token() }}"
            },function(res){

                $('#modal-balance').text('$'+parseFloat(res.new_balance).toFixed(2));
                currentRow.find('.balance-cell').text('$'+parseFloat(res.new_balance).toFixed(2));

                Swal.fire('Success','Balance deducted','success');
            });
        });


        // PASSWORD
        $('#change-password-form').submit(function(e){
            e.preventDefault();

            let newPass = $(this).find('input[name="new_password"]').val();
            let confirmPass = $(this).find('input[name="confirm_password"]').val();

            if(newPass !== confirmPass){
                Swal.fire('Error','Passwords do not match','error');
                return;
            }

            $.post("{{ route('admin.users.update_password') }}",{
                user_id: currentUserId,
                new_password:newPass,
                _token:"{{ csrf_token() }}"
            },function(){
                Swal.fire('Success','Password changed','success');
            });
        });

    </script>
@endpush
