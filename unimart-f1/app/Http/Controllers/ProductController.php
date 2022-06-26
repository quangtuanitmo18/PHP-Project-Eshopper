<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Menu;
use App\Product;
use App\Product_brand;
use App\Product_cat;
use App\Slider;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    //
    public $product_statuses = [
        0 => 'Còn hàng',
        1 => 'Đang về hàng',
        2 => 'Hết hàng'
    ];
    function detail($slug, $id, Request $request)
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();
        $product = Product::find($id);
        Product::where('id', $id)->update([
            'views_count' => $product->views_count + 1,
        ]);
        $brands = Product_brand::where('status', 1)->where('deleted_at', '=', null)->get();

        $product_images = $product->product_images;
        $product_statuses = $this->product_statuses;
        $product_related = Product::where('product_cat_id', $product->product_cat_id)->whereNotIn('id', [$product->id])->where('deleted_at', null)->get();
        $product_tags = $product->tags;

        $comments = Comment::where('status', 1)->whereNotNull('customer_id')->where('product_id', $id)->orderBy('created_at', 'desc')->get();
        $rating_avg = Comment::where('status', 1)->whereNotNull('customer_id')->where('product_id', $id)->avg('rating');
        $SEO_keyword = array();
        foreach ($product_tags as $value) {
            $SEO_keyword[] = $value->name;
        }

        //SEO
        $SEO_desc = $product->description;

        $SEO_keyword = implode(',', $SEO_keyword);
        $SEO_current_url = $request->url();
        $SEO_title = $product->name;
        $SEO = array(
            'SEO_desc' => $SEO_desc,
            'SEO_keyword' => $SEO_keyword,
            'SEO_current_url' => $SEO_current_url,
            'SEO_title' => $SEO_title
        );

        return view('product.detail.index', compact('sliders', 'product_cats', 'menus', 'product', 'product_statuses', 'product_images', 'product_related', 'product_tags', 'comments', 'rating_avg', 'SEO', 'brands'));
    }
    function wishlist()
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $recommended_product = Product::where('best_seller', 1)->where('browse', 1)->where('deleted_at', null)->get();
        $menus = Menu::where('parent_id', 0)->get();
        return view('product.wishlist.list', compact('sliders', 'recommended_product', 'menus'));
    }
    function add_product_comment_ajax(Request $request)
    {
        Comment::create([
            'comment' => $request->comment,
            'product_id' => $request->product_id,
            'customer_id' => $request->user_id,
            'rating' => $request->rating_comment,
        ]);
    }
    function tags(Request $request, $tag, $id)
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();
        // $product_tags = Product::where('slug', 'like', '%' . $tag . '%')->where('browse', 1)->get();
        $product_tags = array();
        $tag = Tag::find($id);
        $tags_product = $tag->products;
        foreach ($tags_product as  $item) {
            if ($item->browse == 1) {
                $product_tags[] = $item;
            }
        }
        $brands = Product_brand::where('status', 1)->where('deleted_at', '=', null)->get();

        $product_statuses = $this->product_statuses;

        $recommended_product = Product::where('best_seller', 1)->where('browse', 1)->where('deleted_at', null)->get();
        return view('product.tag.list', compact('sliders', 'product_cats', 'menus', 'product_statuses', 'recommended_product', 'product_tags', 'tag', 'brands'));
    }
    function search_ajax(Request $request)
    {
        $keyword = $request->keyword;
        $base_url = config('app.base_url');

        $products = Product::where('slug', 'like', '%' . Str::slug($keyword) . '%')->where('browse', 1)->where('deleted_at', null)->get();
        $output = "<ul style='position:absolute; right:15px; ;top:35px; padding-left:5px; background:#f2f0e9; z-index:3 ; width:145%;'>";

        foreach ($products as $product) {
            $output .= "<li style='position:relative; margin-bottom :5px;' id='search_ajax_li'>
            <a  style='color:#fe980f;'>
                <img style='width:70px; height:70px;' src='" . $base_url . $product->thumbnail . "' alt=''>" .
                "<span style='position:absolute; left:80px;'>"
                . $product->name .
                "</span>" .

                "</a>
            </li>";
        }
        $output .= "</ul>";
        echo $output;
    }

    function quick_view(Request $request)
    {
        $base_url = config('app.base_url');

        $id_product = $request->id_product;
        $product = Product::find($id_product);


        $product_statuses = $this->product_statuses;
        $output_product_id = "<b>WED ID: </b>" . $id_product;
        $output_product_name = $product->name;
        $output_product_image =  "<img style='width:100%;' src='" . $base_url . $product->thumbnail . "' alt=''>";

        $ouput_product_detail = " <span>US $</span><span name='product_price' id='product_price'>" . $product->price . "</span>
        <label>Quantity:</label>
        <input class='num_product' type='number' min=1 max='" . $product->qty . "' name='product_qty' style='width:50px; text-align: center' value='1'>
        <button style='margin-bottom:10px; position: relative;
        right: 20px;
        top: 10px;' type='button' data-id_product='" . $product->id . "' name='add-to-cart-quick-view' class='btn btn-fefault cart add-to-cart-quick-view'>
            <i class='fa fa-shopping-cart'></i>
            Thêm vào giỏ hàng
        </button>";
        $output_product_category = "<b>Danh mục: </b>" . $product->product_cat->name;
        $output_product_brand = "<b>Thương hiệu: </b>" . $product->product_brand->name;

        $output_product_availability = "<b>Trạng thái: </b>" . ucfirst($product_statuses[$product->status]) . " <b> ($product->qty)</b>";
        // $output_product_category = "<b>Category: </b>" . strtoupper($product_cat->name) . "";
        $output_product_content = $product->content;

        $output = [
            'output_product_name' => $output_product_name,
            'output_product_image' => $output_product_image,
            'output_product_id' => $output_product_id,

            'ouput_product_detail' => $ouput_product_detail,
            'output_product_availability' => $output_product_availability,
            'output_product_category' => $output_product_category,
            'output_product_brand' => $output_product_brand,
            'output_product_content' => $output_product_content,



        ];
        $request->session()->put('product_quick_view_id', $id_product);
        return json_encode($output);
    }
}
