<?php $base_url = config('app.base_url');
?>
@extends('layouts.master')


@section('title')
<title>About us</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
    }

    html {
        box-sizing: border-box;
    }

    *,
    *:before,
    *:after {
        box-sizing: inherit;
    }

    .column {
        float: left;
        width: 33.3%;
        margin-bottom: 16px;
        padding: 0 8px;
    }

    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        margin: 8px;
    }

    .about-section {
        padding: 50px;

        background-color: #474e5d;
        color: white;
    }

    .container {
        padding: 0 16px;
    }

    .container::after,
    .row::after {
        content: "";
        clear: both;
        display: table;
    }

    .title {
        color: grey;
    }

    .button {
        border: none;
        outline: 0;
        display: inline-block;
        padding: 8px;
        color: white;
        background-color: #000;
        text-align: center;
        cursor: pointer;

    }

    .button:hover {
        background-color: #555;
    }

    .achievement {
        display: flex;
        justify-content: space-around;
    }

    .achievement p.achievement-statistical {
        text-align: center;
        font-size: 30px;
        font-weight: 600;
        color: #ff2d2d;
    }

    .achievement p.achievement-note {
        font-size: 20px;
    }

    @media screen and (max-width: 650px) {
        .column {
            width: 100%;
            display: block;
        }
    }
</style>

@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>

@endsection


@section('content')

<body>





    <section>
        <div class="container">
            <div class="row">
                <class="col-sm-12">

                    <div class="about-us">

                        <h1 style='margin-top:30px;'>Giới thiệu về Eshopper </h1>

                        <p style='font-size:15px;'>
                            Eshopper là một hệ sinh thái thương mại tất cả trong một, gồm các công ty thành viên như:<br><br>

                            - Công ty TNHH Eshopper ("Eshopper") là đơn vị thiết lập, tổ chức sàn thương mại điện tử www.Eshopper.vn để các Nhà bán hàng thể tiến hành một phần hoặc toàn bộ quy trình mua bán hàng hóa, dịch vụ trên sàn thương mại điện tử.<br>
                            - Công ty TNHH TikiNOW Smart Logistics ("TNSL") là đơn vị cung cấp các dịch vụ logistics đầu-cuối, dịch vụ vận chuyển, dịch vụ bưu chính cho Sàn thương mại điện tử www.Eshopper.vn<br>
                            - Công ty TNHH MTV Thương mại Eshopper ("Eshopper Trading") là đơn vị bán hàng hóa, dịch vụ trên sàn thương mại điện tử<br>
                            - Đơn vị bán lẻ Tiki Trading và Sàn Giao dịch cung cấp 10 triệu sản phẩm từ 26 ngành hàng phục vụ hàng triệu khách hàng trên toàn quốc.<br><br>

                            Với phương châm hoạt động “Tất cả vì Khách Hàng”, Eshopper luôn không ngừng nỗ lực nâng cao chất lượng dịch vụ và sản phẩm, từ đó mang đến trải nghiệm mua sắm trọn vẹn cho Khách Hàng Việt Nam với dịch vụ giao hàng nhanh trong 2 tiếng và ngày hôm sau EshopperNOW lần đầu tiên tại Đông Nam Á, cùng cam kết cung cấp hàng chính hãng với chính sách hoàn tiền 111% nếu phát hiện hàng giả, hàng nhái.

                            Thành lập từ tháng 3/2010, Eshopper.vn hiện đang là trang thương mại điện tử lọt top 2 tại Việt Nam và top 6 tại khu vực Đông Nam Á.

                            Eshopper lọt Top 1 nơi làm việc tốt nhất Việt Nam trong ngành Internet/E-commerce 2018 (Anphabe bình chọn), Top 50 nơi làm việc tốt nhất châu Á 2019 (HR Asia bình chọn).
                        </p>
                        <br><br>
                    </div>

                    <div class="about-section">
                        <h1 style='text-align:center;margin-top:0px; margin-bottom:50px;'>Một số thành tựu chúng tôi đã đạt được</h1>

                        <div class="achievement">
                            <div class="achievement-1">
                                <p class='achievement-statistical'>10.145</p>
                                <p class='achievement-note'>Số lượng khách đã mua hàng</p>
                            </div>
                            <div class="achievement-2">
                                <p class='achievement-statistical'>95%</p>
                                <p class='achievement-note'>Số lượng khách hàng hài lòng về dịch vụ của shop</p>
                            </div>
                            <div class="achievement-3">
                                <p class='achievement-statistical'>10</p>
                                <p class='achievement-note'>Số lượng các nhà đầu tư</p>
                            </div>
                        </div>

                    </div>

                    <h2 style="text-align:center">Đội ngũ điều hành</h2>
                    <div class="row">
                        <div class="column">
                            <div class="card">
                                <img src="{{$base_url.'public/uploads/admin/page/about_us/pexels-ali-pazani-2681751.jpg'}}" alt="Jane" style="width:100%">
                                <div class="container">
                                    <h2>Jane Doe</h2>
                                    <p class="title">CEO & Founder</p>
                                    <p>Some text that describes me lorem ipsum ipsum lorem.</p>
                                    <p>jane@example.com</p>
                                    <p><button class="button">Contact</button></p>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="card">
                                <img src="{{$base_url.'public/uploads/admin/page/about_us/pexels-barcelosfotos-2859616.jpg'}}" alt="Mike" style="width:100%">
                                <div class="container">
                                    <h2>Mike Ross</h2>
                                    <p class="title">Art Director</p>
                                    <p>Some text that describes me lorem ipsum ipsum lorem.</p>
                                    <p>mike@example.com</p>
                                    <p><button class="button">Contact</button></p>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="card">
                                <img src="{{$base_url.'public/uploads/admin/page/about_us/pexels-ali-pazani-2887718.jpg'}}" alt="John" style="width:100%">
                                <div class="container">
                                    <h2>John Doe</h2>
                                    <p class="title">Designer</p>
                                    <p>Some text that describes me lorem ipsum ipsum lorem.</p>
                                    <p>john@example.com</p>
                                    <p><button class="button">Contact</button></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2 style="text-align:center">Địa chỉ</h2>

                    <div style='margin-bottom:60px; text-align:center' class="row">

                        <p>Xã Hương sơn, huyện Lạng Giang, tỉnh Bắc Giang.</p>
                        <p>Số điện thoại: +7(123)(456)78-90</p>



                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d59431.626888309176!2d106.27732287006704!3d21.41050081281352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135683a68f5a12b%3A0x5feaec91d7c2205a!2zSMawxqFuZyBTxqFuLCBM4bqhbmcgR2lhbmcsIELhuq9jIEdpYW5nLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2sru!4v1653739531806!5m2!1svi!2sru" width="1000" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    </div>

            </div>

        </div>
        </div>
    </section>
</body>

@endsection