<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        
		<div class="wrapper d-flex align-items-stretch">
            @include('partials.navbar')
            @yield('content')

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

        </div>
        @stack('scripts')
        
    </body>
</html>
