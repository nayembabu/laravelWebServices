@extends('layouts.app')

@section('title', 'সাইন থেকে NID - সার্ভিস বাজার')

@push('styles')
<style>
    .btn-gradient-primary {
        background: linear-gradient(90deg,#0d6efd 60%,#5bc0fe 100%);
        color: #fff;
        border: none;
        font-size: .95rem;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(90deg,#2563eb 60%,#5bc0fe 100%);
        color: #fff;
    }
    .border-dashed {
        border: 2.2px dashed #0d6efd!important;
        background: #f8fafb;
        transition: border-color .2s, box-shadow .2s;
    }
    .custom-drop-area.drag-over {
        border-color: #28a745!important;
        box-shadow: 0 0 10px 2px #cfffd3;
        background: #e9ffef !important;
    }
    .custom-drop-area {
        transition: border-color .18s,background .18s, box-shadow .18s;
    }
    #file-preview small{
        font-size: .92rem;
    }
</style>
@endpush

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">

            @if(session('download_link'))
                <div class="alert alert-success mt-3 text-center ">
                    <div class="mt-2">
                        {{ session('success') }}
                        <a href="{{ session('download_link') }}"
                        class="btn btn-success"
                        target="_blank">
                            NID Download করুন
                        </a>
                    </div>
                </div>
            @endif

            <div class="col-sm-12 col-md-8 col-lg-5">
                <div class="card shadow border-0" style="max-width:340px; margin:auto;">
                    <div class="card-body p-4 bg-light rounded-4">
                        <div id="pdf-upload-area"
                            class="d-flex flex-column align-items-center justify-content-center border-dashed custom-drop-area p-4 rounded-4 w-100 mb-3"
                            style="cursor:pointer;">
                            <span class="bg-white rounded-circle shadow p-2 border border-2 border-primary d-inline-block mb-2">
                                <i class="bi bi-file-earmark-arrow-up" style="font-size: 2.2rem; color:#0d6efd"></i>
                            </span>
                            <div class="mb-2 fw-semibold small" style="color:#222;">PDF আপলোড করুন</div>
                            <div class="text-muted small mb-2" style="font-size:.93rem;">
                                এখানে ড্র্যাগ করুন অথবা <span class="text-primary fw-bold">ব্রাউজ</span>
                            </div>
                            <input type="file" id="pdf-upload-input" accept="application/pdf" style="display:none;">
                            <button type="button" class="btn btn-sm btn-gradient-primary mt-1" id="uploadTrigger">
                                <i class="bi bi-folder2-open"></i> ব্রাউজ
                            </button>
                        </div>
                        <div id="file-preview" class="mb-2 d-none text-center"></div>
                        <div id="upload-success-message" class="alert alert-success d-none text-center py-2 px-3 mb-0 rounded-pill shadow-sm border-0" style="font-size:0.98rem;">
                            <i class="bi bi-check2-circle me-1"></i>Upload
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="user-info-form-container" class="d-none"></div>

@endsection

@push('scripts')

<script>

    $(function(){
        // বাম ছবি ফাইল চুজার ট্রিগার
        $('#photo-upload-trigger-left').on('click', function(e) {
            e.preventDefault();
            $('#photo-upload-input-left').trigger('click');
        });

        // ডান ছবি ফাইল চুজার ট্রিগার
        $('#photo-upload-trigger-right').on('click', function(e) {
            e.preventDefault();
            $('#photo-upload-input-right').trigger('click');
        });

        // বাম ছবি preview
        $('#photo-upload-input-left').on('change', function(e){
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo-preview-left').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // ডান ছবি preview
        $('#photo-upload-input-right').on('change', function(e){
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo-preview-right').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });

    $(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showForm() {
            $('#user-info-form-container').removeClass('d-none').hide().fadeIn();
        }

        function showSuccess(file) {

            var formData = new FormData();
            formData.append('pdf', file);

            $.ajax({
                type: "POST",
                url: "{{ route('user.ni2sign') }}",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function () {
                    $('#user-info-form-container').html(`
                        <div class="text-center ">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <div>তথ্য যাচাইকরণ হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন...</div>
                        </div>
                    `);
                },
                success: function (res) {
                    if (res.hasOwnProperty('error') && res.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'ত্রুটি',
                            text: res.error,
                            confirmButtonText: 'ঠিক আছে'
                        });
                        return false;
                    }
                    $('#user-info-form-container').html(`

                        <div class="card shadow border-0" style="max-width:540px; margin:auto;">
                            <div class="card-body p-4 bg-white rounded-4">
                                <form id="user-info-form" action="{{ route('user.nidSaveData') }}" method="POST" enctype="multipart/form-data" >
                                    @csrf
                                    <input type="hidden" name="voter_id" value="${res.voterid}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="text-center">
                                            <div id="photo-upload-area-left" class="position-relative mb-2" style="width: 70px; height: 70px; margin:auto;">
                                                <img id="photo-preview-left" src="${res.image1}" alt="Uploaded Photo" class="rounded-circle border border-2 border-primary shadow w-100 h-100" style="object-fit:cover;">
                                                <input type="file" id="photo-upload-input-left" name="userPhotoLeft" accept="image/*" style="display: none;">
                                                <button type="button" class="btn btn-sm btn-light shadow rounded-circle position-absolute bottom-0 end-0 border-primary" style="padding:2px 6px; border-width:2px;" id="photo-upload-trigger-left" title="আইডি ফটো">
                                                    <i class="bi bi-camera-fill text-primary"></i>
                                                </button>
                                            </div>
                                            <div class="small text-muted">আইডি ফটো</div>
                                        </div>
                                        <div class="text-center">
                                            <div id="photo-upload-area-right" class="position-relative mb-2" style="width: 70px; height: 70px; margin:auto;">
                                                <img id="photo-preview-right" src="${res.image2}" alt="Uploaded Sign" class="rounded-circle border border-2 border-primary shadow w-100 h-100" style="object-fit:cover;">
                                                <input type="file" id="photo-upload-input-right" name="userSignRight" accept="image/*" style="display: none;">
                                                <button type="button" class="btn btn-sm btn-light shadow rounded-circle position-absolute bottom-0 end-0 border-primary" style="padding:2px 6px; border-width:2px;" id="photo-upload-trigger-right" title="আইডি স্বাক্ষর">
                                                    <i class="bi bi-camera-fill text-primary"></i>
                                                </button>
                                            </div>
                                            <div class="small text-muted">আইডি স্বাক্ষর</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="nidID" class="form-label fw-semibold"> আইডি নাম্বার: </label>
                                            <input type="text" class="form-control" id="nidID" name="nidID" placeholder="আপনার আইডি নাম্বার" value="${res.nid}" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="nidPin" class="form-label fw-semibold">পিন নাম্বার</label>
                                            <input type="text" class="form-control" id="nidPin" name="nidPin" placeholder="পিন নাম্বার লিখুন" value="${res.pin}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="nameBangla" class="form-label fw-semibold"> নাম (বাংলা): </label>
                                            <input type="text" class="form-control" id="nameBangla" name="nameBangla" placeholder="নাম (বাংলা)" value="${res.nameBangla}" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="nameEnglish" class="form-label fw-semibold">নাম (ইংরেজি):</label>
                                            <input type="text" class="form-control" id="nameEnglish" name="nameEnglish" placeholder="নাম (ইংরেজি)" value="${res.nameEnglish}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="fatherName" class="form-label fw-semibold"> পিতার নাম: </label>
                                            <input type="text" class="form-control" id="fatherName" name="fatherName" placeholder="পিতার নাম" value="${res.fatherName}" >
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="motherName" class="form-label fw-semibold">মাতার নাম</label>
                                            <input type="text" class="form-control" id="motherName" name="motherName" placeholder="মাতার নাম" value="${res.motherName}" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="dob" class="form-label fw-semibold"> জন্ম তারিখ: </label>
                                            <input type="text" class="form-control" id="dob" name="dob" value="${res.dateOfBirth}" placeholder="জন্ম তারিখ" >
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="birthPlace" class="form-label fw-semibold">জন্মস্থান:</label>
                                            <input type="text" class="form-control" id="birthPlace" value="${res.birthPlace}" name="birthPlace" placeholder="জন্মস্থান" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label for="gender" class="form-label fw-semibold"> লিঙ্গ: </label>
                                            <input type="text" class="form-control" value="${res.gender}" id="gender" name="gender" placeholder="লিঙ্গ" >
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="bloodGroup" class="form-label fw-semibold">রক্তের গ্রুপ:</label>
                                            <input type="text" class="form-control" id="bloodGroup" value="${res.bloodGroup}" name="bloodGroup" placeholder="রক্তের গ্রুপ" >
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="issueDate" class="form-label fw-semibold">ইস্যু তারিখ</label>
                                            <input type="text" class="form-control" id="issueDate" value="${res.issueDate}" name="issueDate" placeholder="ইস্যু তারিখ" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="fullAddress" class="form-label fw-semibold">ঠিকানা: </label>
                                        <textarea class="form-control" placeholder="আপনার পুরো ঠিকানা লিখুন" name="fullAddress" id="fullAddress" cols="10" rows="3">${res.address}</textarea>
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary">Save & Download</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    `);
                },
                error: function (xhr, status, error) {
                    alert("Upload failed: " + (xhr.responseJSON?.message || error));
                }
            });

            $("#upload-success-message").hide().removeClass('d-none').fadeIn();
        }
        function showPreview(file) {
            if(file && file.type === "application/pdf") {
                let name = file.name;
                let size = (file.size/1024).toFixed(1) + " KB";
                $('#file-preview').removeClass('d-none')
                    .html(`<div class="d-inline-flex align-items-center border rounded-pill bg-white shadow-sm px-2 py-1">
                        <i class="bi bi-file-earmark-pdf text-danger fs-5 me-1"></i>
                        <span class="me-2 small">${name}</span>
                        <small class="badge rounded-pill bg-info text-dark">${size}</small>
                    </div>`);
            } else {
                $('#file-preview').addClass('d-none').empty();
            }
        }
        $("#pdf-upload-area, #uploadTrigger").on('click', function(e){
            if(e.target.id === 'pdf-upload-input') return;
            $("#pdf-upload-input").trigger('click');
        });
        $("#pdf-upload-input").on('change', function(){
            const file = this.files[0];
            if(file && file.type === "application/pdf"){
                showPreview(file);
                showSuccess(file);
                showForm();
            }
        });
        $("#pdf-upload-area").on('dragover', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('drag-over');
        });
        $("#pdf-upload-area").on('dragleave', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('drag-over');
        });
        $("#pdf-upload-area").on('drop', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('drag-over');
            let files = e.originalEvent.dataTransfer.files;
            if(files.length && files[0].type === "application/pdf") {
                $('#pdf-upload-input')[0].files = files;
                showPreview(files[0]);
                showSuccess(files[0]);
                showForm();
            } else {
                $('#file-preview').addClass('d-none').empty();
                $("#upload-success-message").addClass('d-none');
            }
        });
    });
    </script>

@endpush

