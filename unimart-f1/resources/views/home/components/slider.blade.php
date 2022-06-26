<style>
    .carousel-inner {
        background: #f0f0e9;
    }
</style>
<?php $base_url = config('app.base_url');
?>
<section id="slider">
    <!--slider-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#slider-carousel" data-slide-to="1"></li>
                        <li data-target="#slider-carousel" data-slide-to="2"></li>
                    </ol>

                    <div class="carousel-inner">
                        @foreach ($sliders as $k=>$slider)


                        <div class="item {{$k==0?'active':''}}">
                            <div class="col-sm-6">
                                <h1><span>E</span>-SHOPPER</h1>
                                <h2>{{$slider->name}}</h2>
                                <p>{!!$slider->decs!!}</p>
                                <button type="button" class="btn btn-default get">Xem chi tiết</button>
                            </div>
                            <div class="col-sm-6">
                                <img src="{{$base_url.$slider->image_path}}" class="girl img-responsive" alt="" />
                                <img src="{{asset('Eshopper/images/home/pricing.png')}}" class="pricing" alt="" />
                            </div>
                        </div>
                        @endforeach

                    </div>

                    <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
<!--/slider-->