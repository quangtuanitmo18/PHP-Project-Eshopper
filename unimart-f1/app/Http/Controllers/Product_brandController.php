<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Product;
use App\Product_brand;
use App\Product_cat;
use App\Slider;
use Illuminate\Http\Request;

class Product_brandController extends Controller
{
    //
    function index($slug, $id)
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();
        $product_brand = Product_brand::find($id);

        $min_price = Product::min('price');
        $max_price = Product::max('price');

        $min_price_range = 1;
        $max_price_range = $max_price + 100;

        $min_display_price = $min_price;
        $max_display_price = $max_price;
        if (isset($_GET['start_price']) && isset($_GET['end_price'])) {
            $min_display_price = $_GET['start_price'];
            $max_display_price = $_GET['end_price'];
        }

        if (isset($_GET['sort_product'])) {
            $validation_sort = $_GET['sort_product'];
            if ($validation_sort == 'price_from_low_to_high') {
                $products = Product::Where('product_brand_id', $id)->whereBetween('price', [$min_display_price, $max_display_price])->where('deleted_at', null)->orderBy('price')->paginate(12)->appends(request()->query());
            } else if ($validation_sort == 'price_from_high_to_low') {
                $products = Product::Where('product_brand_id', $id)->whereBetween('price', [$min_display_price, $max_display_price])->where('deleted_at', null)->orderBy('price', 'DESC')->paginate(12)->appends(request()->query());
            } else if ($validation_sort == 'aA-zZ') {
                $products = Product::Where('product_brand_id', $id)->whereBetween('price', [$min_display_price, $max_display_price])->where('deleted_at', null)->orderBy('name')->paginate(12)->appends(request()->query());
            } else if ($validation_sort == 'zZ-aA') {
                $products = Product::Where('product_brand_id', $id)->whereBetween('price', [$min_display_price, $max_display_price])->where('deleted_at', null)->orderBy('name', 'DESC')->paginate(12)->appends(request()->query());
            } else {
                $products = Product::where('product_brand_id', $id)->whereBetween('price', [$min_display_price, $max_display_price])->where('deleted_at', null)->paginate(12);
            }
        } else {
            $products = Product::where('product_brand_id', $id)->whereBetween('price', [$min_display_price, $max_display_price])->where('deleted_at', null)->paginate(12);
        }

        $brands = Product_brand::where('status', 1)->where('deleted_at', '=', null)->get();

        return view('product.brand.list', compact('menus', 'products', 'sliders', 'product_cats', 'product_brand', 'min_price', 'max_price', 'min_price_range', 'max_price_range', 'brands'));
    }
}
