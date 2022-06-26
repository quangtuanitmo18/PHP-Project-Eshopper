<?php $base_url = config('app.base_url');
?>

@extends('layouts.master')

@section('SEO')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{$SEO['SEO_desc']}}">
<meta name="keywords" content="{{$SEO['SEO_keyword']}}" />
<meta name="robots" content="INDEX,FOLLOW" />
<link rel="canonical" href="{{$SEO['SEO_current_url']}}" />
<meta name="title" content="{{$SEO['SEO_title']}}" />

<meta property="og:image" content="{{$base_url.$product->thumbnail}}">
<meta property="og:site_name" content="Eshopper.com">
<meta property="og:description" content="{{$SEO['SEO_desc']}}">
<meta property="og:title" content="{{$SEO['SEO_title']}}">
<meta property="og:url" content="{{$SEO['SEO_current_url']}}">
<meta property="og:type" content="website">



@endsection

@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet" /> -->
<link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" rel="stylesheet" />
<link href="https://www.insightindia.com/mcss/icon-font.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<link rel="stylesheet" href="{{asset('menu/menu.css')}}">
<link rel="stylesheet" href="{{asset('SweetAlert/SweetAlert.css')}}">
<link rel="stylesheet" href="{{asset('product/product_related/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('product/product_related/owl.theme.default.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{asset('product/product_detail/rateyo/rateyo.css')}}">

<style>
	.owl-carousel .owl-nav {
		margin-top: 0px !important;
	}

	.owl-carousel .owl-nav button {
		margin-top: 0px !important;
	}

	.owl-item .single-products button {
		margin-bottom: 0px !important;
	}
</style>

<style>
	.slick-slider .slick-prev,
	.slick-slider .slick-next {
		z-index: 100;
		font-size: 2.5em;
		height: 40px;
		width: 40px;
		margin-top: -20px;
		color: #B7B7B7;
		position: absolute;
		top: 50%;
		text-align: center;
		color: #000;
		opacity: .3;
		transition: opacity .25s;
		cursor: pointer;
	}

	.slick-slider .slick-prev:hover,
	.slick-slider .slick-next:hover {
		opacity: .65;
	}

	.slick-slider .slick-prev {
		left: 0;
	}

	.slick-slider .slick-next {
		right: 0;
	}

	#detail .product-images {
		width: 100%;
		margin: 0 auto;
		border: 1px solid #eee;
	}

	#detail .product-images li,
	#detail .product-images figure,
	#detail .product-images a,
	#detail .product-images img {
		display: block;
		outline: none;
		border: none;
	}

	#detail .product-images .main-img-slider figure {
		margin: 0 auto;
		padding: 0 2em;
	}

	#detail .product-images .main-img-slider figure a {
		cursor: pointer;
		cursor: -webkit-zoom-in;
		cursor: -moz-zoom-in;
		cursor: zoom-in;
	}

	#detail .product-images .main-img-slider figure a img {
		width: 100%;
		max-width: 400px;
		margin: 0 auto;
	}

	#detail .product-images .thumb-nav {
		margin: 0 auto;
		padding: 20px 10px;
		max-width: 600px;
	}

	#detail .product-images .thumb-nav.slick-slider .slick-prev,
	#detail .product-images .thumb-nav.slick-slider .slick-next {
		font-size: 1.2em;
		height: 20px;
		width: 26px;
		margin-top: -10px;
	}

	#detail .product-images .thumb-nav.slick-slider .slick-prev {
		margin-left: -30px;
	}

	#detail .product-images .thumb-nav.slick-slider .slick-next {
		margin-right: -30px;
	}

	#detail .product-images .thumb-nav li {
		height: 55px;
		display: block;
		margin: 0 auto;
		cursor: pointer;
	}

	#detail .product-images .thumb-nav li img {
		display: block;
		width: 100%;
		max-width: 75px;
		margin: 0 auto;
		border: 2px solid transparent;
		-webkit-transition: border-color .25s;
		-ms-transition: border-color .25s;
		-moz-transition: border-color .25s;
		transition: border-color .25s;
	}

	#detail .product-images .thumb-nav li:hover,
	#detail .product-images .thumb-nav li:focus {
		border-color: #999;
	}

	#detail .product-images .thumb-nav li.slick-current img {
		border-color: #d12f81;
	}

	ul.thumb-nav .slick-prev i.i-prev,
	ul.thumb-nav .slick-next i.i-next {
		display: none;
		cursor: default;
		pointer-events: none;
	}

	/* .main-img-slider a {
		width: 330px !important;
		height: 380px;
	} */
</style>


@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.0.1/jquery-migrate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>
<script src="{{asset('SweetAlert/SweetAlert.js')}}"></script>
<script src="{{asset('product/product_related/owl.carousel.js')}}"></script>
<script src="{{asset('product/product_detail/rateyo/rateyo.js')}}"></script>
<script>
	/*--------------*/



	// Main/Product image slider for product page
	$('#detail .main-img-slider').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		infinite: true,
		arrows: true,
		fade: true,
		autoplay: true,
		autoplaySpeed: 4000,
		speed: 300,
		lazyLoad: 'ondemand',
		asNavFor: '.thumb-nav',
		prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable">Previous</span></div>',
		nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">Next</span></div>'
	});
	// Thumbnail/alternates slider for product page
	$('.thumb-nav').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		infinite: true,
		centerPadding: '0px',
		asNavFor: '.main-img-slider',
		dots: false,
		centerMode: false,
		draggable: true,
		speed: 200,
		focusOnSelect: true,
		prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable">Previous</span></div>',
		nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">Next</span></div>'
	});
	//keeps thumbnails active when changing main image, via mouse/touch drag/swipe
	$('.main-img-slider').on('afterChange', function(event, slick, currentSlide, nextSlide) {
		//remove all active class
		$('.thumb-nav .slick-slide').removeClass('slick-current');
		//set active class for current slide
		$('.thumb-nav .slick-slide:not(.slick-cloned)').eq(currentSlide).addClass('slick-current');
	});
</script>
<script>
	$(document).ready(function() {
		$(function() {
			var rating_avg = $('#rating_product_avg').val();

			$("#rateYo_rating_product").rateYo({
				rating: rating_avg,
				starWidth: "20px",
				readOnly: true
			});
		});
		$('button.add-to-cart').click(function() {

			var id = $(this).data('id_product');
			var cart_product_id = $('.cart_product_id_' + id).val();
			var _token = $('input[name="_token"]').val();
			$.ajax({

				type: "POST",
				url: "{{ route('cart.add_ajax') }}",
				data: {
					cart_product_id: cart_product_id,
					_token: _token
				},
				success: function() {

					swal({
							title: "Bạn đã thêm sản phẩm vào giỏ hàng thành công!",
							text: "Bạn có muốn đi tới trang giỏ hàng không?",
							type: "success",
							showCancelButton: true,
							confirmButtonClass: 'btn-warning',
							confirmButtonText: 'Có, đi tới!',
							closeOnConfirm: true,
						},
						function() {
							window.location.href = "{{url('cart/show')}}"
						});
				}

			});
		});
	});
</script>
<script>
	$('.owl-carousel').owlCarousel({
		loop: true,
		margin: 10,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 2,
				nav: false
			},
			1000: {
				items: 3,
				nav: true,
				loop: false
			}
		}
	})
</script>

@endsection


@section('content')

<body>

	@include('home.components.slider')




	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					@include('components.sidebar')
				</div>



				<div class="col-sm-9 padding-right">
					<div class="product-details" style="margin-bottom:50px;">
						<!--product-details-->
						<div class="col-sm-5">
							<!-- Wrapper for slides -->
							<section id="detail" style="max-width:100%;">
								<div class="container" style="max-width:100%;">
									<div class="row" style="max-width:100%;">
										<div class="col-md-8 mx-auto" style="width:400px; height:330px; ">
											<!-- Product Images & Alternates -->
											<div class="product-images demo-gallery">
												<!-- Begin Product Images Slider -->
												<div class="main-img-slider">
													@foreach($product_images as $value)
													<a data-fancybox="gallery" href="{{$base_url.$value->image_path}}"><img style="max-width:100%; height:auto;" src="{{$base_url.$value->image_path}}" class="img-fluid"></a>

													@endforeach
												</div>
												<!-- End Product Images Slider -->

												<!-- Begin product thumb nav -->
												<ul class="thumb-nav">
													@foreach($product_images as $value)
													<li style="height:75px;"><img src="{{$base_url.$value->image_path}}"></li>
													@endforeach
												</ul>
												<!-- End product thumb nav -->
											</div>
											<!-- End Product Images & Alternates -->

										</div>
									</div>
								</div>
							</section>
							<!-- Controls -->



						</div>
						<input type="hidden" name="rating_product" id="rating_product_avg" value="{{$rating_avg}}">
						<div class="col-sm-7" style="height:480px; width:450px; margin-left:30px;">
							<form action="{{route('cart.add',$product->id)}}" method="get">
								@csrf
								<div class="product-information" style="padding-bottom:82px; padding-top:20px;">
									<!--/product-information-->
									<img src="{{asset('images/product-details/new.jpg')}}" class="newarrival" alt="" />
									<h2 name="product_name">{{ucfirst($product->name)}}
										<div class="rating_product">
											<span id="rateYo_rating_product" style='padding-left: 0px;'></span>
											<span style=' font-weight: 400; font-size:18px;'>({{number_format($rating_avg,1,'.',',')}}/5)</span>
										</div>


									</h2>
									<p name="product_id">Web ID: {{$product->id}}</p>

									<span>
										<span name="product_price">US ${{number_format($product->price, 1, ',', ' ')}}</span>
										<label>Số lượng:</label>
										<input class="num_product" type="number" min=1 max="{{$product->qty}}" name="product_qty" style="width:50px; text-align: center" value="1">
										<button style='margin-top:10px;' type="submit" data-id_product="{{$product->id}}" name="add-to-cart" class="btn btn-fefault cart add-to-cart1">
											<i class="fa fa-shopping-cart"></i>
											Thêm vào giỏ hàng
										</button>
									</span>
									<p style='margin-top:20px;'><b>Tình trạng: </b>{{ucfirst($product_statuses[$product->status])}} <b> ({{$product->qty}})</b></p>
									<p><b>Danh mục: </b>{{strtoupper($product->product_cat->name)}}</p>
									<p><b>Thương hiệu: </b>{{strtoupper($product->product_brand->name)}}</p>


									<!-- <a href=""><img src="{{asset('Eshopper/images/product-details/share.png')}}" class="share img-responsive" alt="" /></a> -->
									<p><b>Tags: </b></p>
									<span><i class="fas fa-tag"></i></span>
									@foreach($product_tags as $item)
									<?php
									$slug = Str::slug($item->name);
									?>
									<a href="{{route('product.tag',[$slug,$item->id])}}"><span style="margin-top:0px;" class="badge badge-primary">{{$item->name}}</span></a>
									@endforeach
								</div>
								<!--/product-information-->
							</form>
						</div>
					</div>
					<!--/product-details-->

					@include('product.detail.category_tab')

					@include('product.detail.related',['product_related'=>$product_related])

				</div>

			</div>
		</div>
	</section>






</body>

@endsection