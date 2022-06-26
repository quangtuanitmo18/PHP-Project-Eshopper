<?php

namespace App\Http\Controllers;

use App\Post_cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminPost_catController extends Controller
{
    //
    function cat_list(){
        $post_cats_select=Post_cat::all();
        $post_cats_table=Post_cat::all();
        return view('admin.post.cat.list',compact('post_cats_select','post_cats_table'));

    }
    function cat_delete($id){
        $post_cat=Post_cat::find($id);
        $categories=Post_cat::all();
        foreach($categories as $value){
            if($value->parent_id==$post_cat->id){
                return redirect('admin/post/cat/list')->with('status','Bạn cần xóa danh mục con của nó trước khi thực hiện thao tác này');
            }
        }
        $post_cat->delete();
        return redirect('admin/post/cat/list')->with('status','Đã xóa danh mục thành công');
        
 
     }
    function cat_add(Request $request){
       
        $request->validate(
            [
                'name' => 'required|string|max:255',
                
            ],
            [
                'required' =>":attribute không được để trống",
                'max'=>':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name'=>'Tên danh mục',    
            ]
        );
        
        Post_cat::create([
            'name'=>$request->input('name'),
            'slug'=>Str::slug($request->input('name')),
            'parent_id'=>$request->input('parent_id'),
            'user_id'=>Auth::id(),
        ]);
       return  redirect("admin/post/cat/list")->with('status','Thêm danh mục thành công');

    }
    function cat_edit($id){
        $post_cats_select=Post_cat::all();
        $post_cats_table=Post_cat::all();
        $post_cat=Post_cat::find($id);
        return view('admin.post.cat.edit',compact('post_cats_select','post_cats_table','post_cat'));
    }
    
    function cat_update(Request $request,$id){
        $request->validate(
            [
                'name' => 'required|string|max:255',
            ],
            [
                'required' =>":attribute không được để trống",
                'max'=>':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name'=>'Tên danh mục',   
            ]
        );
          
        Post_cat::where('id',$id)->update([
            'name' => $request->input('name'),
            'slug'=> Str::slug($request->input('name')),
            'parent_id'=>$request->input('parent_id'),
            
        ]);
        return redirect('admin/post/cat/list')->with('status','Đã cập nhật danh mục thành công');

    }
    
}
