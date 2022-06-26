<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    //
    function list()
    {
        // $product_cats_select=Product_cat::all();
        // $product_cats_table=Product_cat::all();
        // return view('admin.product.cat.list',compact('product_cats_select','product_cats_table'));
        $role_cats_select = Permission::where('parent_id', 0)->get();
        $role_cats_table = Permission::all();
        $role_cats_1 = array();
        showCategories($role_cats_table, $role_cats_1);
        //return $role_cats;
        //return $role_cats_2;
        foreach ($role_cats_1 as $k => &$item) {
            $role_temp = Permission::find($k);
            $item['key_permission'] = $role_temp->key_permission;
            $item['created_at'] = $role_temp->created_at;
        }
        return view('admin.permission.list', compact('role_cats_select', 'role_cats_1'));
    }
    function add(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',

            ],
            [
                'required' => ":attribute không được để trống",
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên vai trò',
            ]
        );
        $key_permission = "";
        if ($request->input('per_parent') != 0) {

            $per_parent = Permission::find($request->per_parent);

            $key_permission = \Str::slug($per_parent->name) . '_' . \Str::slug($request->name);
        }
        Permission::create([
            'name' => $request->input('name'),
            'display_name' => $request->input('name'),
            'parent_id' => $request->input('per_parent'),
            'key_permission' => $key_permission,
        ]);
        return  redirect("admin/permission/list")->with('status', 'Thêm vai trò thành công');
    }


    function delete($id)
    {
        $permission = Permission::find($id);
        $per_all = Permission::all();
        foreach ($per_all as $value) {
            if ($value->parent_id == $permission->id) {
                return redirect('admin/permission/list')->with('status', 'Bạn cần xóa danh mục con của nó trước khi thực hiện thao tác này');
            }
        }
        $permission->delete();
        return redirect('admin/permission/list')->with('status', 'Đã xóa danh mục thành công');
    }
    function edit($id)
    {

        $permission_cats_select = Permission::all();
        $permission_cats_table = Permission::all();
        $permission_cats_1 = array();
        $permission_cats_2 = array();

        showCategories($permission_cats_table, $permission_cats_2);
        showCategories($permission_cats_select, $permission_cats_1);

        //return $role_cats;
        //return $role_cats_2;
        foreach ($permission_cats_2 as $k => &$item) {
            $permission_temp = Permission::find($k);
            $item['key_permission'] = $permission_temp->key_permission;
            $item['created_at'] = $permission_temp->created_at;
        }
        $permission = Permission::find($id);
        return view('admin.permission.edit', compact('permission_cats_2', 'permission_cats_1', 'permission'));
    }

    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
            ],
            [
                'required' => ":attribute không được để trống",
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên danh mục',
            ]
        );
        $key_permission = "";
        if ($request->input('per_parent') != 0) {

            $per_parent = Permission::find($request->per_parent);

            $key_permission = $per_parent->name . '_' . $request->name;
        }
        Permission::where('id', $id)->update([
            'name' => $request->input('name'),
            'display_name' => $request->input('name'),
            'parent_id' => $request->input('per_parent'),
            'key_permission' => $key_permission,

        ]);
        return redirect('admin/permission/list')->with('status', 'Đã cập nhật vai trò thành công');
    }
}
