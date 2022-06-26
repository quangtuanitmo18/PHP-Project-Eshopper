@extends('layouts.master')

@section('content')

<body>
    <div class="container">



        <h3 style='margin-bottom:20px;'>VNpay </h3>
        <div class="table-responsive">
            <form action="{{route('order.payment_method_VNpay')}}" id="frmCreateOrder" method="post">
                @csrf
                <div class="form-group">
                    <label for="language">Loại hàng hóa</label> <span style="color:red;"> (*)</span>
                    <select name="ordertype" id="ordertype" class="form-control" required>
                        <option value="topup">Nạp tiền điện thoại</option>
                        <option value="billpayment">Thanh toán đơn hàng</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Amount">Tổng tiền</label> <span style="color:red;"> (*)</span>
                    <?php
                    $total_price_VN = $total_price * 23000;
                    ?>
                    <input class="form-control" data-val-number="The field Amount must be a number." id="Amount" name="Amount_disabled" type="text" value="{{$total_price_VN}}VND" disabled />
                    <input type="hidden" name="Amount" value="{{$total_price_VN}}">
                </div>

                <div class="form-group">
                    <label for="OrderDescription">Nội dung mua hàng</label><span style="color:red;"> (*)</span>
                    <textarea class="form-control" cols="20" id="OrderDescription" name="OrderDescription" rows="2" required></textarea>
                </div>


                <div class="form-group">
                    <label for="bankcode">Ngân hàng</label><span style="color:red;"> (*)</span>
                    <select name="bankcode" id="bankcode" class="form-control" required>
                        <option value="MBAPP">App MobileBanking</option>
                        <option value="VNPAYQR">VNPAYQR</option>
                        <option value="VNBANK">LOCAL BANK</option>
                        <option value="IB">INTERNET BANKING</option>
                        <option value="ATM">ATM CARD</option>
                        <option value="INTCARD">INTERNATIONAL CARD</option>
                        <option value="VISA">VISA</option>
                        <option value="MASTERCARD"> MASTERCARD</option>
                        <option value="JCB">JCB</option>
                        <option value="UPI">UPI</option>
                        <option value="VIB">VIB</option>
                        <option value="VIETCAPITALBANK">VIETCAPITALBANK</option>
                        <option value="SCB">Bank SCB</option>
                        <option value="NCB">Bank NCB</option>
                        <option value="SACOMBANK">Bank SacomBank </option>
                        <option value="EXIMBANK">Bank EximBank </option>
                        <option value="MSBANK">Bank MSBANK </option>
                        <option value="NAMABANK">Bank NamABank </option>
                        <option value="VNMART">VnMart</option>
                        <option value="VIETINBANK">Bank Vietinbank </option>
                        <option value="VIETCOMBANK">Bank VCB </option>
                        <option value="HDBANK">Bank HDBank</option>
                        <option value="DONGABANK">Bank Dong A</option>
                        <option value="TPBANK">Bank TPBank </option>
                        <option value="OJB">Bank OceanBank</option>
                        <option value="BIDV">Bank BIDV </option>
                        <option value="TECHCOMBANK">Bank Techcombank </option>
                        <option value="VPBANK">Bank VPBank </option>
                        <option value="AGRIBANK">Bank Agribank </option>
                        <option value="MBBANK">Bank MBBank </option>
                        <option value="ACB">Bank ACB </option>
                        <option value="OCB">Bank OCB </option>
                        <option value="IVB">Bank IVB </option>
                        <option value="SHB">Bank SHB </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="language">Ngôn ngữ</label><span style="color:red;"> (*)</span>
                    <select name="language" id="language" class="form-control" required>
                        <option value="vn">Tiếng Việt</option>
                        <option value="en">Tiếng Anh</option>
                    </select>
                </div>

                <!--<button type="submit" class="btn btn-default" id="btnPopup">Thanh toán Popup</button>-->
                <button type="submit" class="btn btn-default" name="payment_on_vnpay">Thanh Toán</button>
                <!-- <button type="submit" class="btn btn-default" name="payment_on_vnpay_back" onclick="history.back()">Quay lại</button> -->

                <input name="__RequestVerificationToken" type="hidden" value="ZcPEuGYornrHemWfhwICr9bSHjbprDnLyG1IzqnWL70nDCZVjh-Ihqe8G9MWAGd0Xbckl1Hi49HgGfv2rk-ZfkCxFLcnrVJUBj1jyk-8NWM1" />
            </form>
        </div>
        <p>
            &nbsp;
        </p>

        <footer class="footer">
            <p>&copy; VNPAY 2022</p>
        </footer>
    </div> <!-- /container -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/tryitnow/Styles/js/ie10-viewport-bug-workaround.js"></script>

    <link href="https://pay.vnpay.vn/lib/vnpay/vnpay.css" rel="stylesheet" />
    <script src="https://pay.vnpay.vn/lib/vnpay/vnpay.js"></script>
    <script type="text/javascript">
        $("#btnPopup").click(function() {
            var postData = $("#frmCreateOrder").serialize();
            var submitUrl = $("#frmCreateOrder").attr("action");
            $.ajax({
                type: "POST",
                url: submitUrl,
                data: postData,
                dataType: 'JSON',
                success: function(x) {
                    if (x.code === '00') {
                        if (window.vnpay) {
                            vnpay.open({
                                width: 768,
                                height: 600,
                                url: x.data
                            });
                        } else {
                            window.location = x.data;
                        }
                        return false;
                    } else {
                        alert("Error:" + x.Message);
                    }
                }
            });
            return false;
        });
    </script>


    <script>
    </script>
</body>
@endsection