<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMenuController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'menu']);
            return $next($request);
        });
    }
    function list()
    {
        $menu_select = Menu::all();
        $menu_table = Menu::all();
        $menu_1 = array();
        $menu_2 = array();
        showCategories($menu_select, $menu_1);
        showCategories($menu_table, $menu_2);

        //return $role_cats;
        //return $role_cats_2;
        foreach ($menu_2 as $k => &$item) {
            $menu_temp = Menu::find($k);
            $item['slug'] = $menu_temp->slug;
            $item['created_at'] = $menu_temp->created_at;
            $item['url'] = $menu_temp->url;
        }
        return view('admin.menu.list', compact('menu_select', 'menu_1', 'menu_2'));
    }
    function add(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'url' => 'required|string|max:255',

            ],
            [
                'required' => ":attribute không được để trống",
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên menu',
                'url' => 'Url menu',
            ]
        );

        Menu::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'parent_id' => $request->input('menu_parent'),
            'url' => $request->input('url_menu'),


        ]);
        return  redirect("admin/menu/list")->with('status', 'Thêm menu thành công');
    }

    function delete($id)
    {

        $menu = Menu::find($id);
        $menu_all = Menu::all();
        foreach ($menu_all as $value) {
            if ($value->parent_id == $menu->id) {
                return redirect('admin/menu/list')->with('status', 'Bạn cần xóa danh mục con của nó trước khi thực hiện thao tác này');
            }
        }
        $menu->delete();
        return redirect('admin/menu/list')->with('status', 'Đã xóa danh mục thành công');
    }
    function edit($id)
    {

        $menu_select = Menu::all();
        $menu_table = Menu::all();
        $menu_2 = array();
        $menu_1 = array();
        showCategories($menu_select, $menu_1);

        showCategories($menu_table, $menu_2);
        foreach ($menu_2 as $k => &$item) {
            $menu_temp = Menu::find($k);
            $item['slug'] = $menu_temp->slug;
            $item['created_at'] = $menu_temp->created_at;
        }
        $menu = Menu::find($id);
        return view('admin.menu.edit', compact('menu_1', 'menu_2', 'menu'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'url_menu' => 'required|string|max:255',

            ],
            [
                'required' => ":attribute không được để trống",
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên menu',
                'url_menu' => 'Url menu',

            ]
        );


        Menu::where('id', $id)->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'parent_id' => $request->input('menu_parent'),
            'url' => $request->input('url_menu'),

        ]);
        return redirect('admin/menu/list')->with('status', 'Đã cập nhật menu thành công');
    }
}
