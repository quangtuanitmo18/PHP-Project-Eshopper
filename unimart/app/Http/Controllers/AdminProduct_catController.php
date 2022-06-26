<?php

namespace App\Http\Controllers;

use App\Imports\excel_import;
use App\Exports\excel_export;
use Excel;
use App\Product_cat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class AdminProduct_catController extends Controller
{
    //
    function excel_import(Request $request)
    {
        $path = $request->file('excel_import')->getRealPath(); // execel_import chỗ này là tên class
        FacadesExcel::import(new excel_import, $path);
        return back(); // Return lại trang đã gửi yêu cầu
    }
    function excel_export()
    {
        return FacadesExcel::download(new excel_export, 'product_cat_excel.xlsx');
    }
    function cat_list()
    {
        // $cats=Product_cat::all();
        // $brands=array();
        // foreach($cats as $cat){
        //     $brands[$cat->name]=$cat->product_brands;
        // }
        // return $brands;
        // foreach($product_cats as $value){
        //     $product_brands[$value->id]=
        // }
        $product_cats_select = Product_cat::all();
        $product_cats_table = Product_cat::all();
        $menu_1 = array();
        $menu_2 = array();
        showCategories($product_cats_select, $menu_1);
        showCategories($product_cats_table, $menu_2);
        foreach ($menu_2 as $k => &$item) {
            $menu_temp = Product_cat::find($k);
            $item['slug'] = $menu_temp->slug;
            $item['created_at'] = $menu_temp->created_at;
            $item['position'] = $menu_temp->position;
        }
        return view('admin.product.cat.list', compact('menu_1', 'menu_2'));
    }
    function cat_delete($id)
    {
        $product_cat = Product_cat::find($id);
        $categories = Product_cat::all();
        foreach ($categories as $value) {
            if ($value->parent_id == $product_cat->id) {
                return redirect('admin/product/cat/list')->with('status', 'Bạn cần xóa danh mục con của nó trước khi thực hiện thao tác này');
            }
        }
        $product_cat->delete();
        return redirect('admin/product/cat/list')->with('status', 'Đã xóa danh mục thành công');
    }
    function cat_add(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'position' => 'required|integer'

            ],
            [
                'required' => ":attribute không được để trống",
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên danh mục',
                'position' => 'Thứ tự'
            ]
        );

        Product_cat::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'parent_id' => $request->input('parent_cat'),
            'position' => $request->input('position'),
            'user_id' => Auth::id(),
        ]);
        return  redirect("admin/product/cat/list")->with('status', 'Thêm danh mục thành công');
    }
    function cat_edit($id)
    {

        $product_cats_select = Product_cat::all();
        $product_cats_table = Product_cat::all();
        $menu_1 = array();
        $menu_2 = array();
        showCategories($product_cats_select, $menu_1);
        showCategories($product_cats_table, $menu_2);
        foreach ($menu_2 as $k => &$item) {
            $menu_temp = Product_cat::find($k);
            $item['slug'] = $menu_temp->slug;
            $item['created_at'] = $menu_temp->created_at;
            $item['position'] = $menu_temp->position;
        }
        $product_cat = Product_cat::find($id);
        return view('admin.product.cat.edit', compact('menu_1', 'menu_2', 'product_cat'));
    }

    function cat_update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'position' => 'required|integer'

            ],
            [
                'required' => ":attribute không được để trống",
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên danh mục',
                'position' => 'Thứ tự'
            ]
        );

        Product_cat::where('id', $id)->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'position' => $request->input('position'),
            'parent_id' => $request->input('parent_cat'),

        ]);
        return redirect('admin/product/cat/list')->with('status', 'Đã cập nhật danh mục thành công');
    }
}
