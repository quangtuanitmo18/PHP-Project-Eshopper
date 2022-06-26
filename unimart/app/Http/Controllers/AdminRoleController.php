<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class AdminRoleController extends Controller
{
    //
    function __construct(){
        $this->middleware(function($request,$next){ 
            session(['module_active'=>'role']);
            return $next($request);

        });
    }
    function list(Request $request){
        $keyword="";
        $list_act=[
            'delete'=>'Xóa tạm thời',
        ];
        
        if($request->input('keyword') ){
            $keyword=$request->input('keyword');
            
        }
        if($request->input('status')=="trash"){
            $roles=Role::onlyTrashed()->paginate(10);
            $list_act=[
                'restore'=>'Khôi phục',
                'forceDelete'=> 'Xóa vĩnh viễn'
            ];
        }
        else{
            $roles=Role::paginate(10);
        }
        $count_role_active=Role::count();
        $count_role_trash=Role::onlyTrashed()->count();
        $count=[$count_role_active,$count_role_trash];

       
       // return dd($users);
        return view('admin.role.list',compact('roles','count','list_act'));
    }

    function action(Request $request){
        $list_check=$request->input('list_check');
        if(!empty($list_check)){
            

           
                $act=$request->input('act');
                if($act=='delete'){
                    Role::destroy($list_check);
                    return redirect('admin/role/list')->with('status','Bạn đã xóa quyền thành công');
                }
                else if($act=='restore'){
                    Role::withTrashed()->whereIn('id',$list_check)->restore();
                    return redirect('admin/role/list')->with('status','Bạn đã khôi phục quyền thành công');
                }
                else if($act=='forceDelete'){
                    Role::withTrashed()->whereIn('id',$list_check)->forceDelete();
                    return redirect('admin/role/list')->with('status','Bạn đã xóa vĩnh viễn quyền thành công');               
                }
                else{
                    return redirect('admin/role/list')->with('status','Bạn cần chọn 1 thao tác');               

                }
                
        }else{
            return redirect('admin/role/list')->with('status','Bạn cần chọn phần tử để thực thi');
        }

    }

    function add(){
        $permissions_parent=Permission::where('parent_id',0)->get();
         return view('admin.role.add',compact('permissions_parent'));
    }
    function store(Request $request){
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'display_name' => 'required|string|max:255|',
              
            ],
            [
                'required' =>":attribute không được để trống",
                'max'=>':attribute có độ dài tối đa :max kí tự',
               
            ],
            [
                'name'=>'Tên quyền',
                'display_name'=>'Mô tả quyền',
                
            ]

        );
        try{
            DB::beginTransaction();
            $role=Role::create([
                'name'=>$request->input('name'),
                'display_name'=>$request->input('display_name')
            ]);
            $permission_id=$request->input('permission_id');
            $role->permissions()->attach($permission_id);
            DB::commit();
            return redirect('admin/role/list')->with('status','Bạn đã thêm quyền thành công');
    
        }catch(\Exception $exception) {
            DB::rollBack();
            Log::error("message:".$exception->getMessage().'---Line'.$exception->getLine());
        }
       
    }
    function edit($id){
        $role=Role::find($id);
        $permissions=$role->permissions;
        $permissions_parent=Permission::where('parent_id',0)->get();
         return view('admin.role.edit',compact('permissions_parent','role','permissions'));
    }
    function update(Request $request,$id){
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'display_name' => 'required|string|max:255|',
              
            ],
            [
                'required' =>":attribute không được để trống",
                'max'=>':attribute có độ dài tối đa :max kí tự',
               
            ],
            [
                'name'=>'Tên quyền',
                'display_name'=>'Mô tả quyền',
                
            ]

        );
        try{
            DB::beginTransaction();
            Role::where('id',$id)->update([
                'name'=>$request->input('name'),
                'display_name'=>$request->input('display_name')
            ]);
            $role=Role::find($id);
            $permission_id=$request->input('permission_id');
            $role->permissions()->sync($permission_id);
            DB::commit();
            return redirect('admin/role/list')->with('status','Bạn đã cập nhật quyền thành công');
    
        }catch(\Exception $exception) {
            DB::rollBack();
            Log::error("message:".$exception->getMessage().'---Line'.$exception->getLine());
        }
    }
    function delete($id){
        
        $role=Role::find($id);
        $role->delete();
        return redirect('admin/role/list')->with('status','Đã xóa quyền thành công');
        
         
 
     }
    
    
}

