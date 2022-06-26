<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Product;
use App\Product_brand;
use App\Product_cat;
use App\Slider;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $keyword = "";
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();
        $check = 0;

        if ($keyword == "") {
            $product_display = Product::where('featured', 1)->where('browse', 1)->paginate(6);
        } else {
            $product_display = Product::where('slug', 'like', "%" . Str::slug($keyword) . "%")->where('browse', 1)->paginate(6);
            $check = 1;
        }

        $tags_product = Tag::all();

        $brands = Product_brand::where('status', 1)->where('deleted_at', '=', null)->get();

        $recommended_product = Product::where('best_seller', 1)->where('browse', 1)->take(25)->get();

        return view('home.home', compact('sliders', 'product_cats', 'menus', 'product_display', 'tags_product', 'recommended_product', 'check', 'brands'));
    }
}
