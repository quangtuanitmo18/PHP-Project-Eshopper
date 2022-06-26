@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<link rel="stylesheet" href="{{asset('menu/menu.css')}}">

<style>
    /* .login-another a {
        display: inline-block;
        padding: 5px;
        border: 1px solid;
        margin-right: 5px;
    } */

    .register label {
        width: 100% !important;
    }

    .recaptcha {
        margin-bottom: 0px;

    }

    button.submit-register {
        margin-top: 0px !important;

    }

    .invalid-feedback p {
        color: red;
    }
</style>

@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endsection


@section('content')

<body>



    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 padding-right">
                    <section style='margin-top:20px;' id="form">
                        <!--form-->
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/eXjGC5G6_Gg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    <div class="title-video">
                                        <p style=' margin-top:10px; font-size:16px;'>Hướng dẫn đăng kí tài khoản và mua hàng trên Eshopper.</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-sm-offset-1">
                                    <div class="login-form">
                                        <!--login form-->
                                        <!-- <h2>Login to your account</h2> -->
                                        <form class='register' method="POST" action="{{ route('register') }}">
                                            @csrf
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                            <div class="form-group row">
                                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Họ và tên') }}</label>

                                                <div class="col-sm-12">
                                                    <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Fullname">

                                                    @error('name')
                                                    <small class="invalid-feedback" role="alert">
                                                        <p>{{ $message }}</p>
                                                    </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <label style='width:40% !important; ' for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                                                <div class="col-sm-12">
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">

                                                    @error('email')
                                                    <small class="invalid-feedback" role="alert">
                                                        <p>{{ $message }}</p>
                                                    </small>
                                                    @enderror
                                                </div>
                                            </div>



                                            <div class="form-group row">
                                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Mật khẩu') }}</label>

                                                <div class="col-md-12">
                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">

                                                    @error('password')
                                                    <small class="invalid-feedback" role="alert">
                                                        <p>{{ $message }}</p>
                                                    </small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Xác nhận mật khẩu') }}</label>

                                                <div class="col-md-12">
                                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm password" autocomplete="new-password">
                                                </div>
                                            </div>


                                            <div class="form-group row recaptcha">
                                                <div class="col-md-12 offset-md-4">
                                                    <div class="g-recaptcha" data-sitekey="{{env('CAPTCHA_ID')}}"></div>
                                                    <br />
                                                    @if($errors->has('g-recaptcha-response'))
                                                    <small class="invalid-feedback" style="display:block">
                                                        <p>{{$errors->first('g-recaptcha-response')}}</p>
                                                    </small>

                                                    @endif
                                                </div>

                                            </div>



                                            <button class='submit-register' type="submit" class="btn btn-primary">
                                                {{ __('Đăng Ký') }}
                                            </button>

                                            <!-- <div style='margin-top:10px;' class="login-another">
                                                <a href="{{ URL::to('auth/facebook') }}">
                                                    <img style='width:20px; height:20px;' src="https://cdn-icons-png.flaticon.com/512/5968/5968764.png" alt="">
                                                    Facebook</a>
                                                <a href="{{ URL::to('auth/google') }}">
                                                    <img style='width:20px; height:20px;' src=" https://cdn-icons-png.flaticon.com/512/300/300221.png" alt="">

                                                    Google</a>
                                            </div> -->


                                        </form>
                                    </div>
                                    <!--/login form-->
                                </div>


                            </div>
                        </div>
                    </section>
                    <!--/form-->


                </div>
            </div>
        </div>
    </section>





</body>

@endsection

</html>