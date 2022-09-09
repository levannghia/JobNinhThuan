<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link href="{{ asset('site/img/favicon.ico') }}" rel="icon">
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="{{ asset('site/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('site/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('site/css/owlcarousel/owl.theme.default.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('site/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('site/css/toastr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site/css/bootstrap-datetimepicker.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Template Stylesheet -->
    <link href="{{ asset('site/css/style.css?v=' . time()) }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar Start -->
        @include('site.inc.navbar')
        <!-- Navbar End -->

        @if (request()->segment(1) != null)
            @include('site.inc.header')
        @else
            @include('site.inc.slide')
        @endif
        @yield('content')



        <!-- Footer Start -->
        @include('site.inc.footer')
        <!-- Footer End -->


        <!-- Back to Top -->

        <a href="" id="btn_cart_modal" class="btn btn-lg btn-warning btn-lg-square btn-cart"><i class="fa fa-shopping-cart"></i>
            <div class="" style="position: relative;">
                <span class="count-cart">{{ Cart::count() }}</span>
            </div>
        </a>


        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

        <!-- Button trigger modal -->

       @include('site.inc.modal')
    </div>

    <!-- JavaScript Libraries -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v14.0&appId=1107517419829203&autoLogAppEvents=1"
        nonce="OyKWSWej"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="{{ asset('site/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('site/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('site/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('site/js/toastr.min.js') }}"></script>
    <script src="{{ asset('site/js/toastr.init.js') }}"></script>
    <script src="{{ asset('site/js/jquery.validate.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('site/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('site/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('site/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('site/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('site/js/main.js') }}"></script>
    <script src="{{ asset('site/js/app.js?v=' . time()) }}"></script>
    @include('site.inc.script')
    <script>
        $('[data-fancybox="gallery"]').fancybox({
            // Options will go here
        });

        // Remove Items From Cart
        // $(document).on('click','a.remove',function(){
        //     event.preventDefault();
        //     $(this).parent().parent().parent().hide(400);
        // })
        // $('a.remove').click(function() {
        //     event.preventDefault();
        //     $(this).parent().parent().parent().hide(400);
        // })

        // Just for testing, show all items
        $('a.btn.continue').click(function() {
            $('li.items').show(400);
        })
    </script>
    @stack('script')
</body>

</html>
