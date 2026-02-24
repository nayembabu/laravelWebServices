@extends('layouts.app')

@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')

@push('styles')

<style>
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', system-ui, sans-serif;
        padding: 2.5rem 1rem;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
    }
    .header-gradient {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
    }
    .form-control:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
    }
    .btn-modern {
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        border: none;
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }
    .btn-search {
        border-radius: 50px;
        padding: 0.65rem 1.8rem;
        font-weight: 600;
    }
    .table {
        border-radius: 12px;
        overflow: hidden;
    }
    .table thead th {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        font-weight: 600;
        border-bottom: none;
    }
    .table tbody tr {
        transition: background 0.2s;
    }
    .table tbody tr:hover {
        background: rgba(139, 92, 246, 0.08) !important;
    }
    .action-btn {
        font-size: 0.85rem;
        padding: 0.4rem 0.9rem;
        border-radius: 50px;
        margin: 0 3px;
    }
</style>

@endpush

@section('content')


<div class="container">


  <!-- Main Input Card -->
  <div class="glass-card mb-5">
    <div class="header-gradient text-center py-4">
      <h3 class="mb-1 fw-bold">Auto Server Copy</h3>
      <p class="mb-0 opacity-75">NID তথ্য দ্রুত ও নিরাপদে সার্চ করুন</p>
    </div>

    <div class="card-body p-4 p-md-5">
      <form id="searchForm" action="{{ route('user.serverap') }}" method="POST" class="row g-4 align-items-end">
        @csrf
        <div class="col-md-5">
          <label class="form-label fw-semibold">NID নম্বর</label>
          <input type="text" class="form-control form-control-lg" id="nid" name="nid" placeholder="1234567890123" maxlength="20" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">জন্ম তারিখ</label>
          <input type="text" class="form-control form-control-lg dates " id="dob" name="dob" placeholder="YYYY-MM-DD" required>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-modern btn-lg w-100 text-white ">
            তৈরী
          </button>
        </div>
      </form>
    </div>

    <div id="ajaxAlert" class="text-center" ></div>
    <div id="formLoader" class="text-center mt-3" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">অনুগ্রহ করে অপেক্ষা করুন...</p>
    </div>


  </div>

  <!-- Records Table Card -->
  <div class="glass-card">
    <div class="header-gradient d-flex justify-content-between align-items-center px-4 py-3">
      <h5 class="mb-0 fw-bold">পূর্বের রেকর্ডসমূহ</h5>

      <div class="d-flex align-items-center gap-3">
        <label for="filterDate" class="mb-0 text-white fw-medium">তারিখ:</label>
        <input type="text" id="filterDate" class="form-control dates " placeholder="YYYY-MM-DD" style="width: 160px;">
        <button id="applyFilterBtn" class="btn btn-light btn-search">
          <i class="fas fa-search me-1"></i> সার্চ
        </button>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="recordsTable">
          <thead>
            <tr>
              <th>SL</th>
              <th>Date</th>
              <th>NID</th>
              <th>DoB</th>
              <th>Name</th>
              <th>Father's Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>

    <div class="card-footer bg-transparent text-center text-muted border-0 py-3">
      মোট রেকর্ড: <strong id="recordCount"></strong> টি
    </div>
  </div>

</div>


@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        // ✅ CSRF setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#searchForm').on('submit', function (e) {
            e.preventDefault();

            let $form = $(this);
            let url   = $form.attr('action');
            let data  = $form.serialize();
            let $btn  = $form.find('button[type="submit"]');

            // 🔒 Button disable + loader show
            $btn.prop('disabled', true).text('Processing...');
            $('#formLoader').show();
            $('#ajaxAlert').html('');

            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: "json",

                success: function (res) {

                    if (res.ok) {
                        $('#ajaxAlert').html(`
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <strong>✅ Success!</strong> ${res.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    } else {
                        $('#ajaxAlert').html(`
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong>❌ Failed!</strong> ${res.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    }
                },

                error: function (xhr) {

                    let msg = "Server Error";

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }

                    $('#ajaxAlert').html(`
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <strong>❌ Error!</strong> ${msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                },

                complete: function () {
                    // 🔓 Button enable + loader hide
                    $btn.prop('disabled', false).text('তৈরী');
                    $('#formLoader').hide();
                }
            });
        });


        loadTable();

        function loadTable(date = '') {
            $.get('{{ route('user.getserverdata') }}', { date: date }, function(res){

                if(res.ok){

                    let rows = '';
                    let count = 1;

                    res.data.forEach(function(item){

                        rows += `
                            <tr>
                                <td>${count++}</td>
                                <td>${new Date(item.created_at).toISOString().slice(0,10)}</td>
                                <td>${item.nid ?? ''}</td>
                                <td>${new Date(item.dateOfBirth).toISOString().slice(0,10)}</td>
                                <td>${item.nameBangla ?? ''}</td>
                                <td>${item.fatherName ?? ''}</td>
                                <td>
                                    <a href="{{ route('voter.action') }}?id=${item.id}&type=3" class="btn btn-sm bg-success text-white action-btn">
                                        V1
                                    </a>
                                    <a href="{{ route('voter.action') }}?id=${item.id}&type=1" class="btn btn-sm bg-success text-white action-btn">
                                        V2
                                    </a>
                                </td>
                            </tr>
                        `;
                    });

                    $('#tableBody').html(rows);
                    $('#recordCount').text(res.data.length);

                }

            });
        }


        $('#applyFilterBtn').click(function(){
            let date = $('#filterDate').val();
            loadTable(date);
        });











    });







</script>

@endpush

