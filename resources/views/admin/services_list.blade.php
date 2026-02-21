

@extends('layouts.adminapp')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')

@push('styles')

@endpush

@section('content')
    <div class="container">
        <h1 class="mb-4">সকল সার্ভিস</h1>
        <div class="table-responsive">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                Add Service
            </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>নাম</th>
                        <th>Status</th>
                        <th>মূল্য</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody id="services-table-body"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addServiceModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add Service</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text" id="service-name" class="form-control mb-2" placeholder="Service Name">

                    <textarea id="service-description" class="form-control mb-2" placeholder="Description"></textarea>

                    <input type="number" id="service-rate" class="form-control mb-2" placeholder="Rate">

                    <!-- STATUS FIELD -->
                    <select id="service-status" class="form-select mb-2">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" id="save-service">Save</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')

    <script>


        $(document).ready(function(){

            loadServices();

            function loadServices(){
                $.get("{{ route('admin.services.lists') }}", function(data){
                    let html='';
                    data.forEach(service=>{
                        html+=`
                        <tr>
                            <td>${service.id}</td>
                            <td>${service.name}</td>
                            <td>
                                <label class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox" ${service.status==='active'?'checked':''} data-id="${service.id}">
                                </label>
                            </td>
                            <td>
                                <span class="rate-text">${service.rate}</span>
                                <input type="number" class="form-control rate-input d-none" value="${service.rate}">
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm edit-btn"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-success btn-sm update-btn d-none" data-id="${service.id}"><i class="fas fa-check"></i></button>
                            </td>
                        </tr>`;
                    });
                    $('#services-table-body').html(html);
                });
            }

            // Edit Click
            $(document).on('click','.edit-btn',function(){
                let row=$(this).closest('tr');

                row.find('.rate-text').hide();
                row.find('.rate-input').removeClass('d-none');

                $(this).addClass('d-none');
                row.find('.update-btn').removeClass('d-none');
            });

            // Update Rate
            $(document).on('click','.update-btn',function(){
                let row=$(this).closest('tr');
                let id=$(this).data('id');
                let rate=row.find('.rate-input').val();

                $.post("{{ route('admin.services.updateRate') }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                    rate:rate
                },function(){
                    loadServices();
                });
            });

            // Save New Service
            $('#save-service').click(function(){
                $.post("{{ route('admin.services.store') }}",{
                    _token:"{{ csrf_token() }}",
                    name:$('#service-name').val(),
                    description:$('#service-description').val(),
                    rate:$('#service-rate').val(),
                    status: $('#service-status').val(),
                },function(){
                    $('#addServiceModal').modal('hide');
                    loadServices();
                });
            });

            $(document).on('click','.toggle-status',function(){

                let btn=$(this);
                let id=btn.data('id');

                $.post("{{ route('admin.services.toggleStatus') }}",{
                    _token:"{{ csrf_token() }}",
                    id:id
                },function(res){
                    loadServices();
                });

            });


        });



    </script>
@endpush






