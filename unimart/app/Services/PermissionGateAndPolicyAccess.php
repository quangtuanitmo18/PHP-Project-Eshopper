<?php

namespace App\Services;

use Illuminate\Support\Facades\Gate;

class PermissionGateAndPolicyAccess
{


    function set_gate_and_policy_access()
    {

        $this->define_gate_product();
        $this->define_gate_dashboard();
        $this->define_gate_page();
        $this->define_gate_post();
        $this->define_gate_order();
        $this->define_gate_order_coupon();
        $this->define_gate_user();
        $this->define_gate_role();
        $this->define_gate_permission();
        $this->define_gate_menu();
        $this->define_gate_slider();
        $this->define_gate_comment();
        $this->define_gate_video();
        $this->define_gate_setting();
        $this->define_gate_category_post();
        $this->define_gate_category_product();
        $this->define_gate_event();
        $this->define_gate_fee_shipping();
        $this->define_product_brand();
    }
    function define_gate_product()
    {
        Gate::define('product-list', 'App\policies\ProductPolicy@view');
        Gate::define('product-add', 'App\policies\ProductPolicy@create');
        Gate::define('product-edit', 'App\policies\ProductPolicy@update');
        Gate::define('product-delete', 'App\policies\ProductPolicy@delete');
        Gate::define('product-action', 'App\policies\ProductPolicy@action');
    }
    function define_gate_dashboard()
    {
        Gate::define('dashboard-list', 'App\policies\DashboardPolicy@view');
        Gate::define('dashboard-add', 'App\policies\DashboardPolicy@create');
        Gate::define('dashboard-edit', 'App\policies\DashboardPolicy@update');
        Gate::define('dashboard-delete', 'App\policies\DashboardPolicy@delete');
        Gate::define('dashboard-print', 'App\policies\DashboardPolicy@print');
        Gate::define('dashboard-detail', 'App\policies\DashboardPolicy@detail');
    }
    function define_gate_page()
    {
        Gate::define('page-list', 'App\policies\PagePolicy@view');
        Gate::define('page-add', 'App\policies\PagePolicy@create');
        Gate::define('page-edit', 'App\policies\PagePolicy@update');
        Gate::define('page-delete', 'App\policies\PagePolicy@delete');
        Gate::define('page-action', 'App\policies\PagePolicy@action');
    }
    function define_gate_post()
    {
        Gate::define('post-list', 'App\policies\PostPolicy@view');
        Gate::define('post-add', 'App\policies\PostPolicy@create');
        Gate::define('post-edit', 'App\policies\PostPolicy@update');
        Gate::define('post-delete', 'App\policies\PostPolicy@delete');
        Gate::define('post-action', 'App\policies\PostPolicy@action');
    }
    function define_gate_order()
    {
        Gate::define('order-list', 'App\policies\OrderPolicy@view');
        Gate::define('order-add', 'App\policies\OrderPolicy@create');
        Gate::define('order-edit', 'App\policies\OrderPolicy@update');
        Gate::define('order-delete', 'App\policies\OrderPolicy@delete');
        Gate::define('order-detail', 'App\policies\OrderPolicy@detail');
        Gate::define('order-action', 'App\policies\OrderPolicy@action');
    }
    function define_gate_order_coupon()
    {
        Gate::define('order-coupon-list', 'App\policies\Order_couponPolicy@view');
        Gate::define('order-coupon-add', 'App\policies\Order_couponPolicy@create');
        Gate::define('order-coupon-edit', 'App\policies\Order_couponPolicy@update');
        Gate::define('order-coupon-delete', 'App\policies\Order_couponPolicy@delete');
        Gate::define('order-coupon-action', 'App\policies\Order_couponPolicy@action');
    }
    function define_gate_user()
    {
        Gate::define('user-list', 'App\policies\UserPolicy@view');
        Gate::define('user-add', 'App\policies\UserPolicy@create');
        Gate::define('user-edit', 'App\policies\UserPolicy@update');
        Gate::define('user-delete', 'App\policies\UserPolicy@delete');
        Gate::define('user-action', 'App\policies\UserPolicy@action');
    }
    function define_gate_role()
    {
        Gate::define('role-list', 'App\policies\RolePolicy@view');
        Gate::define('role-add', 'App\policies\RolePolicy@create');
        Gate::define('role-edit', 'App\policies\RolePolicy@update');
        Gate::define('role-delete', 'App\policies\RolePolicy@delete');
        Gate::define('role-action', 'App\policies\RolePolicy@action');
    }
    function define_gate_permission()
    {
        Gate::define('permission-list', 'App\policies\PermissionPolicy@view');
        Gate::define('permission-add', 'App\policies\PermissionPolicy@create');
        Gate::define('permission-edit', 'App\policies\PermissionPolicy@update');
        Gate::define('permission-delete', 'App\policies\PermissionPolicy@delete');
        Gate::define('permission-action', 'App\policies\PermissionPolicy@action');
    }
    function define_gate_menu()
    {
        Gate::define('menu-list', 'App\policies\MenuPolicy@view');
        Gate::define('menu-add', 'App\policies\MenuPolicy@create');
        Gate::define('menu-edit', 'App\policies\MenuPolicy@update');
        Gate::define('menu-delete', 'App\policies\MenuPolicy@delete');
        Gate::define('menu-action', 'App\policies\MenuPolicy@action');
    }
    function define_gate_slider()
    {
        Gate::define('slider-list', 'App\policies\SliderPolicy@view');
        Gate::define('slider-add', 'App\policies\SliderPolicy@create');
        Gate::define('slider-edit', 'App\policies\SliderPolicy@update');
        Gate::define('slider-delete', 'App\policies\SliderPolicy@delete');
        Gate::define('slider-action', 'App\policies\SliderPolicy@action');
    }
    function define_gate_comment()
    {
        Gate::define('comment-list', 'App\policies\CommentPolicy@view');
        Gate::define('comment-add', 'App\policies\CommentPolicy@create');
        Gate::define('comment-edit', 'App\policies\CommentPolicy@update');
        Gate::define('comment-delete', 'App\policies\CommentPolicy@delete');
        Gate::define('comment-action', 'App\policies\CommentPolicy@action');
    }

    function define_gate_video()
    {
        Gate::define('video-list', 'App\policies\VideoPolicy@view');
        Gate::define('video-add', 'App\policies\VideoPolicy@create');
        Gate::define('video-edit', 'App\policies\VideoPolicy@update');
        Gate::define('video-delete', 'App\policies\VideoPolicy@delete');
        Gate::define('video-action', 'App\policies\VideoPolicy@action');
    }
    function define_gate_setting()
    {
        Gate::define('setting-list', 'App\policies\SettingPolicy@view');
        Gate::define('setting-add', 'App\policies\SettingPolicy@create');
        Gate::define('setting-edit', 'App\policies\SettingPolicy@update');
        Gate::define('setting-delete', 'App\policies\SettingPolicy@delete');
        Gate::define('setting-action', 'App\policies\SettingPolicy@action');
    }

    function define_gate_category_post()
    {
        Gate::define('category-post-list', 'App\policies\Category_postPolicy@view');
        Gate::define('category-post-add', 'App\policies\Category_postPolicy@create');
        Gate::define('category-post-edit', 'App\policies\Category_postPolicy@update');
        Gate::define('category-post-delete', 'App\policies\Category_postPolicy@delete');
        Gate::define('category-post-action', 'App\policies\Category_postPolicy@action');
    }


    function define_gate_category_product()
    {
        Gate::define('category-product-list', 'App\policies\Category_productPolicy@view');
        Gate::define('category-product-add', 'App\policies\Category_productPolicy@create');
        Gate::define('category-product-edit', 'App\policies\Category_productPolicy@update');
        Gate::define('category-product-delete', 'App\policies\Category_productPolicy@delete');
        Gate::define('category-product-action', 'App\policies\Category_productPolicy@action');
    }

    function define_gate_event()
    {
        Gate::define('event-list', 'App\policies\EventPolicy@view');
        Gate::define('event-add', 'App\policies\EventPolicy@create');
        Gate::define('event-edit', 'App\policies\EventPolicy@update');
        Gate::define('event-delete', 'App\policies\EventPolicy@delete');
        Gate::define('event-action', 'App\policies\EventPolicy@action');
    }

    function define_gate_fee_shipping()
    {
        Gate::define('fee-shipping-list', 'App\policies\Fee_shippingPolicy@view');
        Gate::define('fee-shipping-add', 'App\policies\Fee_shippingPolicy@create');
        Gate::define('fee-shipping-edit', 'App\policies\Fee_shippingPolicy@update');
        Gate::define('fee-shipping-delete', 'App\policies\Fee_shippingPolicy@delete');
        Gate::define('fee-shipping-action', 'App\policies\Fee_shippingPolicy@action');
    }
    function define_product_brand()
    {
        Gate::define('product-brand-list', 'App\policies\Product_brandPolicy@view');
        Gate::define('product-brand-add', 'App\policies\Product_brandPolicy@create');
        Gate::define('product-brand-edit', 'App\policies\Product_brandPolicy@update');
        Gate::define('product-brand-delete', 'App\policies\Product_brandPolicy@delete');
        Gate::define('product-brand-action', 'App\policies\Product_brandPolicy@action');
    }
}
