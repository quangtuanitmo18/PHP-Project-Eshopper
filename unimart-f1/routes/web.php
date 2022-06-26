<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

// Route::get('/login','Auth\LoginController@login')->name('login');
// Route::middleware('auth','verified')->group(function(){

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');

Route::get('/login', 'Auth\LoginController@auth_login')->name('auth.login');
Route::get('/register', 'Auth\RegisterController@auth_register')->name('auth.register');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');


Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/register', 'Auth\RegisterController@register')->name('register');
// Route::post('/register1', 'Auth\LoginController@auth_register1')->name('register1');


Route::get('/product_category/{slug}/{id}', 'Product_catController@index')->name('product_cat.index');
Route::get('product_brand/{slug}/{id}', 'Product_brandController@index')->name('product_brand.index');
Route::get('product_detail/{slug}/{id}', 'ProductController@detail')->name('product.detail');
Route::post('product/quick_view', 'ProductController@quick_view')->name('product.quick_view');


Route::post('cart/change_number_product_ajax', 'CartController@change_number_product_ajax')->name('cart.change_number_product_ajax');
Route::post('Fee_shipping/select_delivery', 'Fee_shippingController@select_delivery')->name('Fee_shipping.select_delivery');
Route::post('Fee_shipping/charge_shipping', 'Fee_shippingController@charge_shipping')->name('Fee_shipping.charge_shipping');

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index');

    Route::get('cart/show', 'CartController@show')->name('cart.show');
    Route::get('cart/add/{id}', 'CartController@add')->name('cart.add');

    Route::get('cart/remove/{rowId}', 'CartController@remove')->name('cart.remove');
    Route::get('cart/destroy', 'CartController@destroy');
    Route::post('cart/update', 'CartController@update')->name('cart.update');
    Route::get('cart/checkout', 'CartController@checkout')->name('cart.checkout');
    Route::post('order/add', 'OrderController@add')->name('order.add');
    Route::get('sendmail/check_order', 'OrderController@sendmail');
    Route::get('order/purchase_order', 'OrderController@purchase_order')->name('purchase.order');
    Route::get('order/cancel/{id}', 'OrderController@cancel')->name('order.cancel');
    route::post('order/check_coupon', "Order_couponController@check_coupon")->name('order.check_coupon');
    route::get('order_coupon/delete', "Order_couponController@delete");


    //VNpay
    route::get('order/payment_method/VNpay/index', 'OrderController@payment_method_VNpay_index')->name('order.payment_method_VNpay_index');
    route::post('order/payment_method/VNpay', 'OrderController@payment_method_VNpay')->name('order.payment_method_VNpay');
    route::get('order/payment_method/VNpay/return', 'OrderController@payment_method_VNpay_return')->name('order.payment_method_VNpay_return');

    // add_comment
    route::post('add/product_comment_ajax', "ProductController@add_product_comment_ajax")->name('add.product_comment_ajax');

    route::get('product/wishlist', 'ProductController@wishlist')->name('product.wishlist');
    route::post('order/detail', 'OrderController@order_detail')->name('order.detail');

    // posts
    //cart

});
Route::POST('cart/add_ajax', 'CartController@add_ajax')->name('cart.add_ajax');
Route::post('cart/cart_quick_view', "CartController@cart_quick_view")->name('cart.cart_quick_view');
Route::get('auth/{social}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{social}/callback', 'Auth\LoginController@handleProviderCallback');

route::get('blogs/list_featured', "PostController@list")->name('blogs.list_featured');
route::get('blogs/category/{slug}/{id}', "PostController@blogs_category")->name('blogs.category');
route::get('blogs/{slug_cat}/{slug_post}/{id}', "PostController@detail")->name('blogs.detail');

// video 
Route::get('videos/list', 'VideoController@list')->name('videos.list');
Route::post('videos/demo_video_ajax', 'VideoController@demo_video_ajax')->name('videos.demo_video_ajax');

// tags
Route::get('product_tag/list/{tag}/{id}', 'ProductController@tags')->name('product.tag');
Route::post('product/search_ajax', 'ProductController@search_ajax')->name('product.search_ajax');

//about

Route::get('about', 'PageController@about')->name('about');

//paypal

Route::get('create-transaction',  'PayPalController@createTransaction')->name('createTransaction');
Route::get('process-transaction', 'PayPalController@processTransaction')->name('processTransaction');
Route::get('success-transaction',  'PayPalController@successTransaction')->name('successTransaction');
Route::get('cancel-transaction', 'PayPalController@cancelTransaction')->name('cancelTransaction');

// });
