@extends('layouts.app')

@section('title', 'Dashboard - সার্ভিস বাজার')

@push('styles')

@endpush

@section('content')


    <!-- Page Content -->
    <div class="page-content">
      <div class="mb-4">
        <h1 class="welcome-text">স্বাগতম, <span class="welcome-gradient">{{auth()->user()->name;}}</span> 👋</h1>
        <p class="text-muted mt-1 mb-0">ড্যাশবোর্ড সারসংক্ষেপ</p>
      </div>
      <!-- Stats -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
              <div class="stat-icon primary"><i class="bi bi-people-fill"></i></div>
              <span class="stat-change up"><i class="bi bi-arrow-up-right"></i> +১২%</span>
            </div>
            <div class="stat-value">১২,৪৫৬</div>
            <div class="stat-label">মোট ব্যবহারকারী</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
              <div class="stat-icon accent"><i class="bi bi-folder-fill"></i></div>
              <span class="stat-change up"><i class="bi bi-arrow-up-right"></i> +৫%</span>
            </div>
            <div class="stat-value">৩৮</div>
            <div class="stat-label">অ্যাক্টিভ প্রজেক্ট</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
              <div class="stat-icon primary"><i class="bi bi-graph-up-arrow"></i></div>
              <span class="stat-change down"><i class="bi bi-arrow-down-right"></i> -৩%</span>
            </div>
            <div class="stat-value">৳৪৫,২৩০</div>
            <div class="stat-label">রেভিনিউ</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
              <div class="stat-icon accent"><i class="bi bi-bar-chart-line-fill"></i></div>
              <span class="stat-change up"><i class="bi bi-arrow-up-right"></i> +৮%</span>
            </div>
            <div class="stat-value">২৩.৫%</div>
            <div class="stat-label">গ্রোথ রেট</div>
          </div>
        </div>
      </div>
      <!-- Activity + Quick Access -->
      <div class="row g-3">
        <div class="col-lg-8">
          <div class="activity-card">
            <h5 class="fw-semibold mb-4">সাম্প্রতিক কার্যকলাপ</h5>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-center gap-3">
                <div class="activity-dot primary"></div>
                <span class="flex-grow-1" style="font-size:14px;">নতুন ব্যবহারকারী যোগ হয়েছে</span>
                <small class="text-muted">২ মিনিট আগে</small>
              </div>
              <div class="d-flex align-items-center gap-3">
                <div class="activity-dot accent"></div>
                <span class="flex-grow-1" style="font-size:14px;">প্রজেক্ট আপডেট সম্পন্ন</span>
                <small class="text-muted">১৫ মিনিট আগে</small>
              </div>
              <div class="d-flex align-items-center gap-3">
                <div class="activity-dot primary"></div>
                <span class="flex-grow-1" style="font-size:14px;">পেমেন্ট গ্রহণ করা হয়েছে</span>
                <small class="text-muted">১ ঘন্টা আগে</small>
              </div>
              <div class="d-flex align-items-center gap-3">
                <div class="activity-dot accent"></div>
                <span class="flex-grow-1" style="font-size:14px;">নতুন রিপোর্ট তৈরি হয়েছে</span>
                <small class="text-muted">৩ ঘন্টা আগে</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="activity-card h-100">
            <h5 class="fw-semibold mb-4">দ্রুত অ্যাক্সেস</h5>
            <div class="row g-3">
              <div class="col-6"><button class="quick-btn primary">রিপোর্ট</button></div>
              <div class="col-6"><button class="quick-btn accent">টিম</button></div>
              <div class="col-6"><button class="quick-btn accent">ফাইল</button></div>
              <div class="col-6"><button class="quick-btn primary">সাহায্য</button></div>
            </div>
          </div>
        </div>
      </div>
    </div>







@endsection






