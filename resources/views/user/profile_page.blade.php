@extends('layouts.app')
@section('title', 'সকল সার্ভিস - সার্ভিস বাজার')


@push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
        }
        .prof-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }
        .prof-page-title {
            font-weight: 700;
            color: #2c3e50;
        }
        .prof-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 6px solid #fff;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .prof-password-toggle {
            cursor: pointer;
        }
        .prof-save-btn {
            padding: 0.75rem 3rem;
            font-size: 1.1rem;
        }
    </style>
@endpush



@section('content')
    <div class="container-fluid">
        <div class="prof-container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="text-center prof-page-title"><i class="bi bi-person-circle me-3 text-primary"></i>আমার প্রোফাইল</h2>

            <!-- Profile Avatar -->
            <div class="text-center mb-2">
                <img src="{{ asset('img/user/' . rand(0,102) . '.png') }}" alt="Profile Picture" class="rounded-circle prof-avatar">
            </div>

            <!-- Profile Edit Form -->
            <form id="profForm" action="{{ route('user.profileupdate') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label for="profName" class="form-label fw-semibold">নাম</label>
                        <input type="text" class="form-control form-control-lg" id="profName" name="name" value="{{ auth()->user()->name; }}" required>
                    </div>

                    <!-- Username -->
                    <div class="col-md-6">
                        <label for="profUsername" class="form-label fw-semibold">ইউজারনেম</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control" name="username" id="profUsername" value="{{ auth()->user()->username; }}" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="profEmail" class="form-label fw-semibold">ইমেইল</label>
                        <input type="email" class="form-control form-control-lg" name="email" id="profEmail" value="{{ auth()->user()->email; }}" required>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label for="profPhone" class="form-label fw-semibold">মোবাইল নাম্বার</label>
                        <input type="tel" class="form-control form-control-lg" name="phone" id="profPhone" value="{{ auth()->user()->phone; }}" required>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label for="profPassword" class="form-label fw-semibold">নতুন পাসওয়ার্ড (যদি পরিবর্তন করতে চান)</label>
                        <div class="input-group input-group-lg">
                            <input type="password" class="form-control" name="password" id="profPassword" placeholder="নতুন পাসওয়ার্ড লিখুন">
                            <span class="input-group-text prof-password-toggle"><i class="bi bi-eye"></i></span>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <label for="profConfirmPassword" class="form-label fw-semibold">পাসওয়ার্ড নিশ্চিত করুন</label>
                        <div class="input-group input-group-lg">
                            <input type="password" class="form-control" name="password_confirmation" id="profConfirmPassword" placeholder="আবার লিখুন">
                            <span class="input-group-text prof-password-toggle"><i class="bi bi-eye"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-success btn-lg prof-save-btn"><i class="bi bi-check2-circle me-2"></i>আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('.prof-password-toggle').on('click', function() {
                const input = $(this).closest('.input-group').find('input');
                const icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });
        });
    </script>
@endpush









