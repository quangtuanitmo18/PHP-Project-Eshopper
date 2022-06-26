<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-ku modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style=" overflow:auto; height:632px; border-radius: 0px!important;background-clip: border-box;">
            <div class="modal-header" style="position:fixed; ;width: 100%; background: #FE980F;z-index: 3;padding-bottom: 0px!important; border-radius: 0px!important;background-clip: border-box;">
                <h5 class="modal-title"> Xem nhanh </h5>
                <button type="button" style="    position: relative;top: -12px;" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="top: 40px;position: relative;padding: 20px;">
                <div class="product-details" style="margin-bottom:50px;">
                    <!--product-details-->
                    <form id="form_quick_view">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 product-information" style="height:450px;">
                                <!-- Wrapper for slides -->
                                <section id="detail" style="width:100%; padding-bottom:26px!important;">
                                    <div class="container" style="width:100%;">
                                        <div class="product-images demo-gallery" style="width:100%;">
                                            <div class="row" style="width:100%;" id="product_image">

                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Controls -->
                            </div>
                            <div class="col-sm-6 product-information" style="height:450px;">
                                <div style="padding-bottom:50px;">
                                    <!--/product-information-->
                                    <img src="{{ $base_url.'public/images/product-details/new.jpg'}}" class="newarrival" alt="" />
                                    <h2 name="product_name" id="product_name"></h2>
                                    <p name="product_id" id="product_id">
                                    </p>
                                    <img name="product_thumbnail" src="{{asset('Eshopper/images/product-details/rating.png')}}" alt="" />
                                    <span id="product_detail">
                                    </span>

                                    <p style='margin-top:20px' id="product_availability"></p>
                                    <p id="product_category"> </p>
                                    <p id="product_brand"> </p>


                                    <a href="{{url('cart/show')}}" class="btn btn-primary">Giỏ hàng</a>
                                    <!-- <a href=""><img src="{{asset('Eshopper/images/product-details/share.png')}}" class="share img-responsive" alt="" /></a> -->

                                </div>
                                <!--/product-information-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title">
                                    <p style="font-weight: bold; line-height: 1.428571429; margin-top:20px;">Chi tiết sản phẩm</p>
                                </div>
                                <div id="product_content">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div> -->
        </div>
    </div>
</div>