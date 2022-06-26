<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

Route::get('/', function () {
    return Redirect::route('dashboard');
});

Auth::routes(['verify' => true]);
Route::get('/login', 'Auth\LoginController@auth_login')->name('auth.login');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');



Route::get('/home', 'HomeController@index')->name('home');
Route::get("/home/admin", "HomeController@home_admin")->name('home.admin');;
Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
Route::middleware('auth')->group(function () {

    // Route::get('/dashboard', 'AdminDashboardController@show')->middleware('verified');
    Route::get('/admin', 'AdminDashboardController@show');
    Route::get('/dashboard', 'AdminDashboardController@show')->name('dashboard')->middleware('can:dashboard-list');
    Route::get('dashboard/order/detail/{id}', 'AdminDashboardController@order_detail')->name('dashboard.order.detail')->middleware('can:dashboard-detail');
    Route::get('dashboard/order/delete/{id}', 'AdminDashboardController@delete')->name('dashboard.order.delete')->middleware('can:dashboard-delete');
    Route::post('dashboard/order/update/{id}', 'AdminDashboardController@update')->name('dashboard.order.update')->middleware('can:dashboard-edit');
    Route::get('dashboard/order/print/{id}', 'AdminDashboardController@order_print')->name('dashboard.order.print')->middleware('can:dashboard-print');
    Route::post('dashboard/filter_sales', 'AdminDashboardController@filter_sales')->name('dashboard.filter_sales');
    Route::post('dashboard/chart_set_30day', 'AdminDashboardController@chart_set_30day')->name('dashboard.chart_set_30day');

    // Route::get('/admin','AdminDashboardController@show')->middleware('verified');

    Route::get('admin/menu/list', 'AdminMenuController@list')->middleware('can:menu-list');
    Route::post('admin/menu/add', 'AdminMenuController@add')->middleware('can:menu-add');;
    Route::get('admin/menu/edit/{id}', 'AdminMenuController@edit')->name('admin.menu.edit')->middleware('can:menu-edit');;
    Route::get('admin/menu/delete/{id}', 'AdminMenuController@delete')->name('admin.menu.delete')->middleware('can:menu-delete');;
    Route::post('admin/menu/update/{id}', 'AdminMenuController@update')->name('admin.menu.update');

    Route::get('admin/event/list', 'AdminEventController@list')->middleware('can:event-list');
    Route::post('admin/event/add', 'AdminEventController@add')->middleware('can:event-add');
    Route::get('admin/event/edit/{id}', 'AdminEventController@edit')->name('admin.event.edit')->middleware('can:event-edit');
    Route::get('admin/event/delete/{id}', 'AdminEventController@delete')->name('admin.event.delete')->middleware('can:event-delete');
    Route::post('admin/event/update/{id}', 'AdminEventController@update')->name('admin.event.update');

    Route::get('admin/slider/list', 'AdminSliderController@list')->middleware('can:slider-list');
    Route::get('admin/slider/action', 'AdminSliderController@action')->middleware('can:slider-action');
    Route::get('admin/slider/add', 'AdminSliderController@add')->middleware('can:slider-add');
    Route::get('admin/slider/edit/{id}', 'AdminSliderController@edit')->name('admin.slider.edit')->middleware('can:slider-edit');;
    Route::get('admin/slider/delete/{id}', 'AdminSliderController@delete')->name('admin.slider.delete')->middleware('can:slider-delete');
    Route::post('admin/slider/update/{id}', 'AdminSliderController@update')->name('admin.slider.update');
    Route::post('admin/slider/store', 'AdminSliderController@store');
    Route::post('admin/slider/show_image_ajax', 'AdminSliderController@show_image_ajax')->name('admin.slider.show_image_ajax');
    Route::post('admin/slider/insert_image_ajax', 'AdminSliderController@insert_image_ajax')->name('admin.slider.insert_image_ajax');
    Route::post('admin/slider/delete_image_ajax', 'AdminSliderController@delete_image_ajax')->name('admin.slider.delete_image_ajax');

    //product_brand
    Route::get('admin/brand/list', 'AdminProduct_brandController@list')->middleware('can:product-brand-list');
    Route::get('admin/brand/action', 'AdminProduct_brandController@action')->middleware('can:product-brand-action');
    Route::get('admin/brand/add', 'AdminProduct_brandController@add')->middleware('can:product-brand-add');
    Route::get('admin/brand/edit/{id}', 'AdminProduct_brandController@edit')->name('admin.brand.edit')->middleware('can:product-brand-edit');
    Route::get('admin/brand/delete/{id}', 'AdminProduct_brandController@delete')->middleware('can:product-brand-delete')->name('admin.brand.delete');
    Route::post('admin/brand/update/{id}', 'AdminProduct_brandController@update')->name('admin.brand.update');
    Route::post('admin/brand/store', 'AdminProduct_brandController@store');
    Route::post('admin/brand/show_image_ajax', 'AdminProduct_brandController@show_image_ajax')->name('admin.brand.show_image_ajax');
    Route::post('admin/brand/insert_image_ajax', 'AdminProduct_brandController@insert_image_ajax')->name('admin.brand.insert_image_ajax');
    Route::post('admin/brand/delete_image_ajax', 'AdminProduct_brandController@delete_image_ajax')->name('admin.brand.delete_image_ajax');

    //comment
    Route::get('admin/comment/list', 'AdminCommentController@list')->middleware('can:comment-list');
    Route::get('admin/comment/action', 'AdminCommentController@action')->middleware('can:comment-action');;
    Route::get('admin/comment/add', 'AdminCommentController@add')->middleware('can:comment-add');
    Route::get('admin/comment/edit/{id}', 'AdminCommentController@edit')->name('admin.comment.edit')->middleware('can:comment-edit');
    Route::get('admin/comment/delete/{id}', 'AdminCommentController@delete')->name('admin.comment.delete')->middleware('can:comment-delete');
    Route::post('admin/comment/update/{id}', 'AdminCommentController@update')->name('admin.comment.update');
    Route::post('admin/comment/store', 'AdminCommentController@store');
    Route::post('admin/comment/status', 'AdminCommentController@status')->name('admin.comment.status');
    Route::post('admin/comment/add_reply_ajax', 'AdminCommentController@add_reply_ajax')->name('admin.comment.add_reply_ajax');
    Route::post('admin/comment/show_reply_ajax', 'AdminCommentController@show_reply_ajax')->name('admin.comment.show_reply_ajax');
    Route::post('admin/comment/delete_reply_ajax', 'AdminCommentController@delete_reply_ajax')->name('admin.comment.delete_reply_ajax');
    Route::post('admin/comment/update_reply_ajax', 'AdminCommentController@update_reply_ajax')->name('admin.comment.update_reply_ajax');
    Route::post('admin/comment/list_reply_ajax', 'AdminCommentController@list_reply_ajax')->name('admin.comment.list_reply_ajax');
    Route::post('admin/comment/edit_status_ajax', 'AdminCommentController@edit_status_ajax')->name('admin.comment.edit_status_ajax');

    Route::get('admin/setting/list', 'AdminSettingController@list')->middleware('can:setting-list');
    Route::get('admin/setting/action', 'AdminSettingController@action')->middleware('can:setting-action');
    Route::get('admin/setting/add', 'AdminSettingController@add')->name('admin.setting.add')->middleware('can:setting-add');
    Route::get('admin/setting/edit/{id}', 'AdminSettingController@edit')->name('admin.setting.edit')->middleware('can:setting-edit');
    Route::get('admin/setting/delete/{id}', 'AdminSettingController@delete')->name('admin.setting.delete')->middleware('can:setting-delete');
    Route::post('admin/setting/update/{id}', 'AdminSettingController@update')->name('admin.setting.update');
    Route::post('admin/setting/store', 'AdminSettingController@store')->name('admin.setting.store');

    Route::get('admin/user/list', 'AdminUserController@list')->middleware('can:user-list');
    Route::get('admin/user/add', 'AdminUserController@add')->middleware('can:user-add');
    Route::POST('admin/user/store', 'AdminUserController@store');
    Route::get('admin/user/delete/{id}', 'AdminUserController@delete')->name('admin.user.delete')->middleware('can:user-delete');
    Route::get('admin/user/edit/{id}', 'AdminUserController@edit')->name('admin.user.edit')->middleware('can:user-edit');
    Route::POST('admin/user/update/{id}', 'AdminUserController@update')->name('admin.user.update');
    Route::get('admin/user/action', 'AdminUserController@action')->middleware('can:user-action');
    Route::get('admin/user/search', 'AdminUserController@search');

    Route::get('admin/product/list', 'AdminProductController@list')->middleware('can:product-list');
    Route::get('admin/product/delete/{id}', 'AdminProductController@delete')->name('admin.product.delete')->middleware('can:product-delete');
    Route::get('admin/product/action', 'AdminProductController@action')->middleware('can:product-action');
    Route::get('admin/product/add', 'AdminProductController@add')->middleware('can:product-add');
    Route::post('admin/product/store', 'AdminProductController@store');
    Route::get('admin/product/edit/{id}', 'AdminProductController@edit')->name('admin.product.edit')->middleware('can:product-edit');
    Route::post('admin/product/update/{id}', 'AdminProductController@update')->name('admin.product.update');
    Route::post('admin/product/excel_import_product', 'AdminProductController@excel_import')->name('admin.product.cat.excel_import_product');
    Route::post('admin/product/excel_export_product', 'AdminProductController@excel_export')->name('admin.product.cat.excel_export_product');
    Route::post('admin/product/show_image_ajax', 'AdminProductController@show_image_ajax')->name('admin.product.show_image_ajax');
    Route::post('admin/product/delete_image_ajax', 'AdminProductController@delete_image_ajax')->name('admin.product.delete_image_ajax');
    Route::post('admin/product/insert_image_ajax', 'AdminProductController@insert_image_ajax')->name('admin.product.insert_image_ajax');
    Route::get('admin/product/dropzone_show/{id}', 'AdminProductController@dropzone_show')->name('admin.product.dropzone_show');
    Route::post('admin/product/dropzone_upload/{id}', 'AdminProductController@dropzone_upload')->name('admin.product.dropzone_upload');
    Route::get('admin/product/dropzone_fetch', 'AdminProductController@dropzone_fetch')->name('admin.product.dropzone_fetch');
    Route::get('admin/product/dropzone_delete', 'AdminProductController@dropzone_delete')->name('admin.product.dropzone_delete');


    Route::get('admin/product/cat/list', 'AdminProduct_catController@cat_list')->middleware('can:category-product-list');
    Route::get('admin/product/cat/edit/{id}', 'AdminProduct_catController@cat_edit')->name('admin.product.cat.edit')->middleware('can:category-product-edit');
    Route::get('admin/product/cat/delete/{id}', 'AdminProduct_catController@cat_delete')->name('admin.product.cat.delete')->middleware('can:category-product-delete');
    Route::post('admin/product/cat/add', 'AdminProduct_catController@cat_add')->middleware('can:category-product-add');
    Route::post('admin/product/cat/update/{id}', 'AdminProduct_catController@cat_update')->name('admin.product.cat.update');
    Route::post('admin/product/cat/excel_import', 'AdminProduct_catController@excel_import')->name('admin.product.cat.excel_import');
    Route::post('admin/product/cat/excel_export', 'AdminProduct_catController@excel_export')->name('admin.product.cat.excel_export');



    Route::get('admin/order/list', 'AdminOrderController@list')->middleware('can:order-list');
    Route::get('admin/order/action', 'AdminOrderController@action')->middleware('can:order-action');;
    Route::get('admin/order/delete/{id}', 'AdminOrderController@delete')->name('admin.order.delete')->middleware('can:order-delete');
    Route::post('admin/order/update/{id}', 'AdminOrderController@update')->name('admin.order.update')->middleware('can:order-edit');
    Route::get('admin/order/detail/{order_id}', 'AdminOrder_detailController@show')->name('admin.order.detail')->middleware('can:order-detail');;

    Route::get('admin/order/coupon/list', 'AdminOrder_couponController@list')->middleware('can:order-coupon-list');
    Route::get('admin/order/coupon/add', 'AdminOrder_couponController@add')->middleware('can:order-coupon-add');
    Route::post('admin/order/coupon/store', 'AdminOrder_couponController@store');

    Route::get('admin/order/coupon/action', 'AdminOrder_couponController@action')->middleware('can:order-coupon-action');
    Route::get('admin/order/coupon/delete/{id}', 'AdminOrder_couponController@delete')->name('admin.order.coupon.delete')->middleware('can:order-coupon-delete');
    Route::get('admin/order/coupon/edit/{id}', 'AdminOrder_couponController@edit')->name('admin.order.coupon.edit')->middleware('can:order-coupon-edit');
    Route::post('admin/order/coupon/update/{id}', 'AdminOrder_couponController@update')->name('admin.order.coupon.update');


    Route::get('admin/post/list', 'AdminPostController@list')->middleware('can:post-list');
    Route::get('admin/post/delete/{id}', 'AdminPostController@delete')->name('admin.post.delete')->middleware('can:post-delete');
    Route::get('admin/post/edit/{id}', 'AdminPostController@edit')->name('admin.post.edit')->middleware('can:post-edit');
    Route::get('admin/post/action', 'AdminPostController@action')->middleware('can:post-action');
    Route::post('admin/post/update/{id}', 'AdminPostController@update')->name('admin.post.update');
    Route::get('admin/post/add', 'AdminPostController@add')->middleware('can:post-add');
    Route::post('admin/post/store', 'AdminPostController@store');

    Route::get('admin/post/cat/list', 'AdminPost_catController@cat_list')->middleware('can:category-post-list');
    Route::get('admin/post/cat/edit/{id}', 'AdminPost_catController@cat_edit')->name('admin.post.cat.edit')->middleware('can:category-post-edit');
    Route::get('admin/post/cat/delete/{id}', 'AdminPost_catController@cat_delete')->name('admin.post.cat.delete')->middleware('can:category-post-delete');
    Route::post('admin/post/cat/add', 'AdminPost_catController@cat_add')->middleware('can:category-post-add');
    Route::post('admin/post/cat/update/{id}', 'AdminPost_catController@cat_update')->name('admin.post.cat.update');
    Route::post('admin/post/show_image_ajax', 'AdminPostController@show_image_ajax')->name('admin.post.show_image_ajax');
    Route::post('admin/post/insert_image_ajax', 'AdminPostController@insert_image_ajax')->name('admin.post.insert_image_ajax');
    Route::post('admin/post/delete_image_ajax', 'AdminPostController@delete_image_ajax')->name('admin.post.delete_image_ajax');


    Route::get('admin/page/list', 'AdminPageController@list')->middleware('can:page-list');
    Route::get('admin/page/delete/{id}', 'AdminPageController@delete')->name('admin.page.delete')->middleware('can:page-delete');
    Route::get('admin/page/edit/{id}', 'AdminPageController@edit')->name('admin.page.edit')->middleware('can:page-edit');
    Route::get('admin/page/action', 'AdminPageController@action')->middleware('can:page-action');
    Route::post('admin/page/update/{id}', 'AdminPageController@update')->name('admin.page.update');
    Route::get('admin/page/add', 'AdminPageController@add')->middleware('can:page-add');
    Route::post('admin/page/store', 'AdminPageController@store');

    Route::get('admin/role/list', 'AdminRoleController@list')->middleware('can:role-list');
    Route::get('admin/role/edit/{id}', 'AdminRoleController@edit')->name('admin.role.edit')->middleware('can:role-edit');
    Route::get('admin/role/delete/{id}', 'AdminRoleController@delete')->name('admin.role.delete')->middleware('can:role-delete');
    Route::get('admin/role/action', 'AdminRoleController@action')->name('admin.role.action')->middleware('can:role-action');
    Route::get('admin/role/add', 'AdminRoleController@add')->middleware('can:role-add');
    Route::post('admin/role/store', 'AdminRoleController@store');
    Route::post('admin/role/update/{id}', 'AdminRoleController@update')->name('admin.role.update');


    Route::get('admin/permission/list', 'AdminPermissionController@list')->name('admin.permission.list')->middleware('can:permission-list');
    Route::post('admin/permission/add', 'AdminPermissionController@add')->name('admin.permission.add')->middleware('can:permission-add');
    Route::get('admin/permission/edit/{id}', 'AdminPermissionController@edit')->name('admin.permission.edit')->middleware('can:permission-edit');
    Route::get('admin/permission/delete/{id}', 'AdminPermissionController@delete')->name('admin.permission.delete')->middleware('can:permission-delete');
    Route::post('admin/permission/update/{id}', 'AdminPermissionController@update')->name('admin.permission.update');


    // video 
    Route::get('admin/video/list', 'AdminVideoController@list')->middleware('can:video-list');
    Route::get('admin/video/action', 'AdminVideoController@action')->middleware('can:video-action');
    Route::get('admin/video/add', 'AdminVideoController@add')->middleware('can:video-add');
    Route::get('admin/video/edit/{id}', 'AdminVideoController@edit')->name('admin.video.edit')->middleware('can:video-edit');
    Route::get('admin/video/delete/{id}', 'AdminVideoController@delete')->name('admin.video.delete')->middleware('can:video-delete');
    Route::post('admin/video/update/{id}', 'AdminVideoController@update')->name('admin.video.update');
    Route::post('admin/video/store', 'AdminVideoController@store');
    Route::post('admin/video/show_image_ajax', 'AdminVideoController@show_image_ajax')->name('admin.video.show_image_ajax');
    Route::post('admin/video/insert_image_ajax', 'AdminVideoController@insert_image_ajax')->name('admin.video.insert_image_ajax');
    Route::post('admin/video/demo_video_ajax', 'AdminVideoController@demo_video_ajax')->name('admin.video.demo_video_ajax');


    // Fee_shipping
    Route::get('admin/Fee_shipping/list', 'AdminFee_shippingController@list')->middleware('can:fee-shipping-list');
    Route::post('admin/Fee_shipping/add', 'AdminFee_shippingController@add')->name('admin.Fee_shipping.add')->middleware('can:fee-shipping-add');
    Route::get('admin/Fee_shipping/delete/{id}', 'AdminFee_shippingController@delete')->name('admin.Fee_shipping.delete')->middleware('can:fee-shipping-delete');
    Route::post('admin/Fee_shipping/edit/{id}', 'AdminFee_shippingController@edit')->name('admin.Fee_shipping.edit')->middleware('can:fee-shipping-edit');
    Route::Post('admin/Fee_shipping/select_delivery', 'AdminFee_shippingController@select_delivery')->name('admin.Fee_shipping.select_delivery');
});
