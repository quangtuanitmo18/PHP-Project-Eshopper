<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Page;
use App\Slider;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    function about()
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $menus = Menu::where('parent_id', 0)->get();

        $page_about = Page::where('title', 'about')->first();

        return view('page.about', compact('sliders', 'menus', 'page_about'));
    }
}
