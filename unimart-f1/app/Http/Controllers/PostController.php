<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Post;
use App\Post_cat;
use App\Product_cat;
use App\Slider;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public $product_statuses = [
        0 => 'Chờ duyệt',
        1 => 'Công khai',

    ];
    function list()
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();

        $post_featured = Post::where('post_featured', 1)->where('deleted_at', null)->where('status', 1)->paginate(3);
        $post_cats = Post_cat::all();
        return view('post.list', compact('sliders', 'menus', 'product_cats', 'post_featured', 'post_cats'));
    }
    function blogs_category($slug, $id)
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();

        $post_cats = Post_cat::all();

        $post_category = Post_cat::find($id);


        $posts = $post_category->posts;

        $post_id = array();
        foreach ($posts as $value) {
            $post_id[] = $value->id;
        }

        $posts = Post::whereIn('id', $post_id)->paginate(3);


        return view('post.category.list', compact('sliders', 'menus', 'product_cats', 'post_cats', 'posts', 'post_category'));
    }

    function detail($slug_cat, $slug_post, $id)
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();


        $post_cats = Post_cat::all();
        $post = Post::find($id);
        $post->update([
            'views_count' => $post->views_count + 1,
        ]);
        $post_cat = $post->post_cat;
        $post_related = Post::where('post_cat_id', $post_cat->id)->whereNotIn('id', [$id])->get();
        return view('post.detail', compact('sliders', 'menus', 'product_cats', 'post_cats', 'post_cat', 'post', 'post_related'));
    }
}
