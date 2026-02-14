
<nav class="navbar navbar-expand-lg admin-topbar">
    <div class="container-fluid">
        <a class="navbar-brand admin-brand" href="#"><i class="bi bi-shield-lock me-2"></i>Admin Panel</a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <!-- Menu Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link admin-nav-link active" href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-2"></i>ড্যাশবোর্ড</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link" href="#"><i class="bi bi-people me-2"></i>ইউজার</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link" href="{{ route('admin.order_waiting') }}"><i class="bi bi-cart-check me-2"></i>অর্ডার</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link" href="{{ route('admin.mybox_order_waiting') }}"><i class="bi bi-bag me-2"></i>আমার বক্স</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link" href="#"><i class="bi bi-wallet2 me-2"></i>ডিপোজিট</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link" href="#"><i class="bi bi-graph-up me-2"></i>রিপোর্ট & অ্যানালিটিক্স</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link" href="#"><i class="bi bi-gear me-2"></i>সেটিংস</a>
                </li>
            </ul>

            <!-- Right Side -->
            <div class="d-flex align-items-center gap-4">
                <div class="dropdown">
                    <a class="dropdown-toggle text-white text-decoration-none d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-3 me-2"></i>
                        <span>Admin</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>প্রোফাইল</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>লগআউট</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>



