<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'সার্ভিস বাজার')</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('img/serlogo.png') }}">
        <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="{{ asset('plugin/bt5/css/bootstrap.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/bt-icons/font/bootstrap-icons.min.css') }}">

         <link rel="stylesheet" href="{{ asset('plugin/fa/css/all.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/jquery-ui/jquery-ui.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/sa2/dist/sweetalert2.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/toastr/build/toastr.min.css') }}">

         <!-- Google Fonts -->
         <link rel="stylesheet" href="{{ asset('plugin/css/googlefonts.css') }}">
         <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('plugin/css/app.css') }}">

        @stack('styles')


        <!-- ALL JS -->
        <script src="{{ asset('plugin/bt5/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugin/jquery-4.0.0.min.js') }}"></script>
        <script src="{{ asset('plugin/fa/js/all.min.js') }}"></script>
        <script src="{{ asset('plugin/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('plugin/sa2/dist/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('plugin/toastr/build/toastr.min.js') }}"></script>

    </head>
    <body>

            @include('partials.navbar')




            <!-- Main Content -->
            <div class="main-content" id="mainContent">
                <!-- Top Header -->
                <div class="top-header">
                    <button class="toggle-btn" id="toggleSidebar" aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <button class="toggle-btn position-relative">
                            @php
                                $user = auth()->user();
                            @endphp
                            <span class="balance-info" style="font-size:16px; font-weight:500;">
                                ব্যালেন্স:
                                <span class="text-success">
                                    {{ isset($user) && method_exists($user, 'balance') ? number_format($user->balance(), 2) : 'N/A' }}
                                    <span style="font-size:13px;">৳</span>
                                </span>
                            </span>
                        </button>
                        <div class="header-avatar">A</div>
                    </div>
                </div>

                @yield('content')

            </div>

            @push('scripts')
            <script>
                $(document).ready(function(){
                    $(".dates").datepicker({
                        dateFormat: "yy-mm-dd",
                        changeMonth: true,
                        changeYear: true,
                        showAnim: "slideDown"
                    });

                    @if(session('success'))
                        toastr.success("{{ session('success') }}");
                    @endif
                    @if(session('error'))
                        toastr.error("{{ session('error') }}");
                    @endif
                });
            </script>
            <script>
                $(function () {
                  const $sidebar = $('#sidebar');
                  const $mainContent = $('#mainContent');
                  const $overlay = $('#sidebarOverlay');
                  const $toggleBtn = $('#toggleSidebar');
                  const isMobile = () => window.innerWidth < 992;
                  // Toggle sidebar
                  $toggleBtn.on('click', function () {
                    if (isMobile()) {
                      $sidebar.toggleClass('mobile-open');
                      $overlay.toggleClass('show');
                    } else {
                      $sidebar.toggleClass('collapsed');
                      $mainContent.toggleClass('expanded');
                    }
                  });
                  // Close on overlay click
                  $overlay.on('click', function () {
                    $sidebar.removeClass('mobile-open');
                    $overlay.removeClass('show');
                  });

                //   // Active menu item
                //   $('.menu-link').on('click', function (e) {
                //     e.preventDefault();
                //     $('.menu-link').removeClass('active');
                //     $(this).addClass('active');
                //     // Close mobile sidebar on click
                //     if (isMobile()) {
                //       $sidebar.removeClass('mobile-open');
                //       $overlay.removeClass('show');
                //     }
                //   });
                  // Handle window resize
                  $(window).on('resize', function () {
                    if (!isMobile()) {
                      $sidebar.removeClass('mobile-open');
                      $overlay.removeClass('show');
                    }
                  });
                });
            </script>

            <!--Start of Tawk.to Script
            <script type="text/javascript">
                var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                (function(){
                    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                    s1.async=true;
                    s1.src='https://embed.tawk.to/635ffd56daff0e1306d4f2ab/1ggnfb70a';
                    s1.charset='UTF-8';
                    s1.setAttribute('crossorigin','*');
                    s0.parentNode.insertBefore(s1,s0);
                })();
            </script>-->
            <!--End of Tawk.to Script-->
            @endpush

        @stack('scripts')

    </body>
</html>
