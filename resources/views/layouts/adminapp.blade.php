<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'সার্ভিস বাজার')</title>

        <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="{{ asset('plugin/bt5/css/bootstrap.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/bt-icons/font/bootstrap-icons.min.css') }}">

         <link rel="stylesheet" href="{{ asset('plugin/fa/css/all.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/jquery-ui/jquery-ui.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/sa2/dist/sweetalert2.min.css') }}">
         <link rel="stylesheet" href="{{ asset('plugin/toastr/build/toastr.min.css') }}">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('plugin/css/app.css') }}">

        <style>
            body {
                background: #f4f6f9;
                min-height: 100vh;
            }
            .admin-topbar {
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
                padding: 0.75rem 0;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1030;
            }
            .admin-brand {
                font-weight: 700;
                font-size: 1.6rem;
                color: #fff !important;
            }
            .admin-nav-link {
                color: rgba(255,255,255,0.9) !important;
                font-weight: 500;
                padding: 0.5rem 1rem !important;
                transition: all 0.3s;
            }
            .admin-nav-link:hover,
            .admin-nav-link.active {
                color: #fff !important;
                background: rgba(255,255,255,0.15);
                border-radius: 0.5rem;
            }
            .admin-balance {
                background: rgba(255,255,255,0.2);
                border-radius: 50px;
                padding: 0.5rem 1.2rem;
                color: #fff;
                font-weight: 600;
            }
            .admin-main {
                margin-top: 70px;
                padding: 2rem;
            }
        </style>

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

        @include('partials.topmenu')

        <div class="admin-main">
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

                    (function($) {
                        "use strict";
                        var fullHeight = function() {
                            $('.js-fullheight').css('height', $(window).height());
                            $(window).resize(function(){
                                $('.js-fullheight').css('height', $(window).height());
                            });
                        };
                        fullHeight();
                        $('#sidebarCollapse').on('click', function () {
                            $('#sidebar').toggleClass('active');
                        });
                    })(jQuery);
                    @if(session('success'))
                        toastr.success("{{ session('success') }}");
                    @endif
                    @if(session('error'))
                        toastr.error("{{ session('error') }}");
                    @endif
                });
            </script>
        @endpush

        @stack('scripts')

    </body>
</html>
