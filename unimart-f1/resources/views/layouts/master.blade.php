<!-- Stored in resources/views/layouts/app.blade.php -->

<html>
<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="utf-8">
    @yield('SEO')
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="keywords" content="thol store, trang bị thể hình, quần áo gym, thực phẩm bổ sung" />
    <meta name="robots" content="INDEX,FOLLOW" />
    <link  rel="canonical" href="https://www.thol.com.vn/" /> -->
    <!-- <meta name="title" content="THOL - Thực phẩm bổ sung thể hình, phụ kiện tập GYM VIP" /> -->


    <meta name="csrf-token" content="{{ csrf_token() }}">



    @yield('title')
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v13.0&appId=635361301104445&autoLogAppEvents=1" nonce="d5I5WWyq"></script>
    <link href="{{asset('Eshopper/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('Eshopper/css/font-awesome.min.css')}}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <link href="{{asset('Eshopper/css/prettyPhoto.css')}}" rel="stylesheet">
    <link href="{{asset('Eshopper/css/price-range.css')}}" rel="stylesheet">
    <link href="{{asset('Eshopper/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('Eshopper/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">

    @yield('css')
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="shortcut icon" type="image/png" href="{{asset('logo/Eshopper.png')}}" />

    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head>


<body>


    @include('components.header')
    <div style='position: relative;top: 225px;' class="wrapper-container">
        @yield('content')
    </div>
    @include('components.footer')

</body>

<script src="{{asset('Eshopper/js/jquery.js')}}"> </script>

<script src="{{asset('Eshopper/js/bootstrap.min.js')}}"></script>
<script src="{{asset('Eshopper/js/jquery.scrollUp.min.js')}}"></script>
<script src="{{asset('Eshopper/js/price-range.js')}}"></script>
<script src="{{asset('Eshopper/js/jquery.prettyPhoto.js')}}"></script>
<script src="{{asset('Eshopper/js/main.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@yield('js')

</html>