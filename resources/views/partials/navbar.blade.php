@php
$serviceRates = \App\Models\Service::whereIn('id', [18,19,20,22,23])
    ->pluck('rate', 'id')
    ->toArray();
@endphp

<!-- $user = auth()->user();
$userBalance = $user->balance();
$orders = $user->serviceOrders;  -->
{{--
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="{{ route('user.dashboard') }}"><span class="fa fa-home mr-3"></span> Home</a>
          </li>
          <li>
              <a href="{{ route('user.services') }}"><i class="fa-brands fa-servicestack mr-3 notif"></i> Services</a>
          </li>
          <li>
              <a href="{{ route('user.downloads') }}"><i class="fa fa-download mr-3 notif"></i> Download </a>
          </li>
          <li>
              <a href="{{ route('user.deposite') }}"><i class="fa fa-dollar mr-3 notif"></i> Payment </a>
          </li>
          <li>
              <a href="{{ route('user.profile') }}"><i class="fa fa-user mr-3 notif"></i> Profile </a>
          </li>
          <li>
            <a href="{{ route('logout') }}"><span class="fa fa-sign-out mr-3"></span> LogOut</a>
          </li>
        </ul>
        @endauth
 --}}


  <!-- Sidebar Overlay (mobile) -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>
  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
      <div class="sidebar-brand-icon">
        <img src="{{ asset('img/serlogo.png') }}" alt="S" height="60px" width="60px" srcset="">
      </div>
      <span class="sidebar-brand-text"> ServicesBazar</span>
    </div>
    <!-- Menu -->
    <div class="sidebar-menu">
      <div class="menu-label">প্রধান মেনু</div>
      <ul class="list-unstyled">
        <li class="menu-item">
          <a href="{{ route('user.dashboard') }}" class="menu-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" data-tooltip="ড্যাশবোর্ড">
            <i class="bi bi-grid-1x2-fill"></i>
            <span class="link-text">ড্যাশবোর্ড</span>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('user.sign_nid') }}" class="menu-link {{ request()->routeIs('user.sign_nid') ? 'active' : '' }}" data-tooltip="অ্যানালিটিক্স">
            <i class="bi bi-person-vcard"></i>
            <span class="link-text">সাইন টু এনআইডি <span class="bg-dark p-1 rounded">{{ $serviceRates[18] ?? 0 }}/-</span> </span>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('user.server') }}" class="menu-link {{ request()->routeIs('user.server') ? 'active' : '' }}" data-tooltip="ব্যবহারকারী">
            <i class="bi bi-person-badge-fill"></i>
            <span class="link-text">সার্ভার কপি <span class="bg-dark p-1 rounded">{{ $serviceRates[23] ?? 0 }}/-</span> </span>
          </a>
        </li>





{{--
        <li class="menu-item">
          <a href="#" class="menu-link" data-tooltip="ব্যবহারকারী">
            <i class="bi bi-person-badge-fill"></i>
            <span class="link-text">ই-টিন মেক <span class="bg-dark p-1 rounded">{{ $serviceRates[22] ?? 0 }}/-</span> </span>
          </a>
        </li>
--}}






{{--
        <li class="menu-item">
          <a href="#" class="menu-link" data-tooltip="ব্যবহারকারী">
            <i class="bi bi-person-badge-fill"></i>
            <span class="link-text">সাইন টু সার্ভার <span class="bg-dark p-1 rounded">{{ $serviceRates[19] ?? 0 }}/-</span> </span>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link" data-tooltip="প্রজেক্ট">
            <i class="bi bi-credit-card-2-front-fill"></i>
            <span class="link-text">জন্ম নিবন্ধন মেইক <span class="bg-dark p-1 rounded">{{ $serviceRates[20] ?? 0 }}/-</span> </span>
          </a>
        </li> --}}
        <li class="menu-item">
          <a href="{{ route('user.services') }}" class="menu-link {{ request()->routeIs('user.services') ? 'active' : '' }}" data-tooltip="ক্যালেন্ডার">
            <i class="bi bi-card-text"></i>
            <span class="link-text">সকল অর্ডার</span>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('user.downloads') }}" class="menu-link {{ request()->routeIs('user.downloads') ? 'active' : '' }}" data-tooltip="ক্যালেন্ডার">
            <i class="bi bi-download"></i>
            <span class="link-text">ডাউনলোড অর্ডার</span>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('user.download_make') }}" class="menu-link {{ request()->routeIs('user.download_make') ? 'active' : '' }}" data-tooltip="ক্যালেন্ডার">
            <i class="bi bi-download"></i>
            <span class="link-text">ডাউনলোড মেক</span>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('pay.form') }}" class="menu-link {{ request()->routeIs('pay.form') ? 'active' : '' }}" data-tooltip="ক্যালেন্ডার">
            <i class="fa fa-dollar"></i>
            <span class="link-text">রিচার্জ</span>
          </a>
        </li>
      </ul>


      <div class="menu-label mt-3">সিস্টেম</div>
      <ul class="list-unstyled">
        <li class="menu-item">
          <a href="#" class="menu-link" data-tooltip="সেটিংস">
            <i class="bi bi-gear-fill"></i>
            <span class="link-text">সেটিংস</span>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('logout') }}" class="menu-link text-danger-hover" data-tooltip="লগ আউট">
            <i class="bi bi-box-arrow-left"></i>
            <span class="link-text">লগ আউট</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- Footer -->
    <div class="sidebar-footer">
      <a href="{{ route('user.profile') }}" class="sidebar-user text-decoration-none" >
        <div class="user-avatar"><img src="{{ asset('img/user/' . rand(0,102) . '.png') }}" height="40px" width="40px"  alt="U"></div>
        <div class="user-info">
          <div class="user-name">{{auth()->user()->name;}}</div>
          <div class="user-role">{{auth()->user()->referral_code;}}</div>
        </div>
      </a>
    </div>
  </nav>

