<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Slider;
use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    //
    function list()
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->get();
        //$product_cats = Product_cat::where('parent_id', 0)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();

        //$post_featured = Post::where('post_featured', 1)->where('status', 1)->paginate(3);
        //$post_cats = Post_cat::all();

        $videos = Video::where('status', 1)->where('deleted_at', null)->orderBy('created_at', 'DESC')->paginate(6);
        return  view('video.list', compact('videos', 'sliders', 'menus'));
    }
    function demo_video_ajax(Request $request)
    {

        $id_video = $request->id_video;
        $video = Video::find($id_video);
        $output_title = $video->title;
        $output_desc = $video->desc;
        $output_video = '<iframe width="100%" height="500" src="https://www.youtube.com/embed/' . substr($video->link, 17) . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        $output = [
            'output_title' => $output_title,
            'output_desc' => $output_desc,
            'output_video' => $output_video
        ];
        return json_encode($output);
    }
}
