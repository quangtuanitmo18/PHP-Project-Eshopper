<style>
    .social-icons ul.navbar-nav li:hover {
        background: #428bca !important;
    }

    .header-fixed {
        position: fixed;
        width: 100%;
        z-index: 1200;

    }

    .btn-group .dropdown-menu a {
        color: #ffffff;
    }

    .header-middle {
        background: #333333;
    }

    .header-bottom {
        background: #f0f0e9;
        background: #f0f0e9;
        width: 100%;
        position: absolute;
        top: 100px;
    }

    .dropdown-menu {
        left: 15px !important;
        background: #333333;
        top: 37px;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script>
    $(document).ready(function() {
        $('#keyword').keyup(function() {
            var keyword = $(this).val();
            var form_data = new FormData();
            if (keyword != '') {
                form_data.append("keyword", keyword);
                $.ajax({
                    url: "{{ route('product.search_ajax') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $("#search-ajax").css({
                            "display": "block"
                        })
                        $("#search-ajax").html(data);
                    }
                });
            } else {
                $("#search-ajax").css({
                    "display": "none"
                })
            }
        });
        $(document).on('click', 'li#search_ajax_li', function() {

            var str = $(this).text();

            $("#keyword").val(str.trim());
            $("#search-ajax").css({
                "display": "none"
            })
        });
    });
    $("*").not("#search-ajax").on("click", function() {
        $("#search-ajax").css({
            "display": "none"
        })
    });
</script>

<header id="header">
    <!--header-->
    <div class="header-fixed">
        <div class="header_top">
            <!--header_top-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contactinfo">
                            <ul class="nav nav-pills">
                                <li><a href="#"><i class="fa fa-phone"></i> {{get_config_value('phone_number')}}</a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i> {{get_config_value('email')}}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="social-icons pull-right">
                            <ul class="nav navbar-nav">
                                <li><a href="{{get_config_value('link_fb')}}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{get_config_value('link_vk')}}" target="_blank"><i class="fa fa-vk"></i></a></li>
                                <li><a href="{{get_config_value('link_youtube')}}" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i>
                                    </a></li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/header_top-->

        <div class="header-middle">
            <!--header-middle-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo pull-left">
                            <a href="{{url("")}}"><img src="{{asset('Eshopper/images/home/logo.png')}}" alt="" /></a>
                        </div>
                        <div class="btn-group pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                    USA
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Canada</a></li>
                                    <li><a href="#">UK</a></li>
                                </ul>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                    DOLLAR
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Canadian Dollar</a></li>
                                    <li><a href="#">Pound</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="shop-menu pull-right">
                            <ul class="nav navbar-nav">

                                <li><a href="{{route('product.wishlist')}}"><i class="fa fa-star"></i> Yêu Thích</a></li>
                                <li><a href="{{route('cart.checkout')}}"><i class="fa fa-crosshairs"></i> Thanh Toán</a></li>
                                <li><a href="{{route('cart.show')}}"><i class="fa fa-shopping-cart"></i> Giỏ Hàng</a></li>
                                {{-- @if(Auth::check())
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="fa fa-lock"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                </li>

                                @else
                                <li><a href="{{ route('login') }}"><i class="fa fa-lock"></i> Đăng Nhập</a></li>
                                @endif --}}

                                @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Đăng Nhập') }}</a>
                                </li>
                                @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Đăng Ký') }}</a>
                                </li>
                                @endif
                                @else
                                <li class="nav-item dropdown">

                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style=" border: none;
                                        box-shadow: none;
                                        ">



                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="margin-block-end: auto;">
                                            @csrf

                                            <a class="dropdown-item" href="">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                                {{ __('Tài Khoản') }}

                                            </a><br>
                                            <a class="dropdown-item" href="{{route("purchase.order")}}">
                                                <i class="fa fa-list" aria-hidden="true"></i>

                                                {{ __('Đơn Hàng') }}

                                            </a><br>
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                <i class="fa fa-sign-out" aria-hidden="true"></i>

                                                {{ __('Đăng Xuất') }}

                                            </a>
                                        </form>
                                    </div>
                                </li>
                                @endguest

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/header-middle-->

    </div>


    <div class="header-bottom">
        <!--header-bottom-->
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    @include('components.main_menu')
                </div>
                <form action="{{url("/home")}}" method="GET">
                    <div class="col-sm-3">

                        <div class="" style="position:absolute ;width:90%;">

                            <input type="text" placeholder="Search" name="keyword" id="keyword" style=" position:absolute; right:2px; width:160%; height:30px; " value="{{request()->input('keyword')}}" />

                        </div>

                        <div id="search-ajax" style="width:100%;">
                            <ul style='position:absolute; right:15px; ;top:35px; padding-left:5px; background:#f2f0e9; z-index:3 ; width:145%;'>
                                <!-- <li style='position:relative; margin-bottom:5px;'>

                                    <a href="">
                                        <img style="width:70px; height:70px;" alt="">
                                        <span style='position:absolute; left:80px;'>san pham 1</span>
                                    </a>

                                </li>
                                <li><a href="">1</a></li>
                                <li><a href="">1</a></li> -->
                            </ul>
                        </div>

                        <input type="submit" name="btn_search" value="Tìm kiếm" class="btn btn-primary" style='position: relative;top: 14px;right: -179px; margin-top:25px;'>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/header-bottom-->
</header>
<!--/header-->