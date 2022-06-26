<?php

namespace App\Http\Controllers;

use App\Role;
use App\Role_user;
use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $keyword = "";
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        if ($request->input('status') == "trash") {
            $users = User::onlyTrashed()->where('name', 'LIKE', "%{$keyword}%")->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $users = User::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }
        $count_user_active = User::count();
        $count_user_trash = User::onlyTrashed()->count();
        $count = [$count_user_active, $count_user_trash];


        // return dd($users);
        return view('admin.user.list', compact('users', 'count', 'list_act'));
    }
    function add()
    {
        $roles = Role::all();
        return view('admin.user.add', compact('roles'));
    }
    function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $roles_of_user = User::find($id)->roles;
        return view('admin.user.edit', compact('user', 'roles_of_user', 'roles'));
    }
    function store(Request $request)
    {

        // dd($request->all());
        // return $request->role_id;
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|'
            ],
            [
                'required' => ":attribute không được để trống",
                'min' => ':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
                'confirmed' => 'xác nhận mật khẩu không thành công'
            ],
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu'
            ]
        );

        try {
            \DB::beginTransaction();
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
            $roles_id = $request->input('role_id');
            // foreach($roles_id as $item){
            //     Role_user::create([
            //         'role_id'=>$item,
            //         'user_id'=>$user->id
            //     ]);
            // }
            $user->roles()->attach($roles_id);
            \DB::commit();
            return redirect('admin/user/list')->with('status', 'Đã thêm thành viên thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            Log::error("message:" . $exception->getMessage() . '---Line' . $exception->getLine());
        }
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8|'
            ],
            [
                'required' => ":attribute không được để trống",
                'min' => ':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
                'confirmed' => 'xác nhận mật khẩu không thành công'
            ],
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu'
            ]


        );
        try {
            \DB::beginTransaction();
            User::where('id', $id)->update([
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
            ]);
            $user = User::find($id);
            $roles_id = $request->input('role_id');
            // foreach($roles_id as $item){
            //     Role_user::create([
            //         'role_id'=>$item,
            //         'user_id'=>$user->id
            //     ]);
            // }
            $user->roles()->sync($roles_id);
            \DB::commit();
            return redirect('admin/user/list')->with('status', 'Đã cập nhật thành viên thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            Log::error("message:" . $exception->getMessage() . '---Line' . $exception->getLine());
        }
    }
    function delete($id)
    {
        if (Auth::id() != $id) {
            $user = User::find($id);
            $user->delete();
            return redirect('admin/user/list')->with('status', 'Đã xóa thành viên thành công');
        } else {
            return redirect('admin/user/list')->with('status', 'Bạn không thể tự xóa mình ra khỏi hệ thống');
        }
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {
            foreach ($list_check as $k => $id) {
                if (Auth::id() == $id) {
                    unset($list_check[$k]);
                }
            }

            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'delete') {
                    User::destroy($list_check);
                    return redirect('admin/user/list')->with('status', 'Bạn đã xóa thành công');
                } else if ($act == 'restore') {
                    User::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('admin/user/list')->with('status', 'Bạn đã khôi phục thành công');
                } else if ($act == 'forceDelete') {
                    User::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('admin/user/list')->with('status', 'Bạn đã xóa vĩnh viễn user thành công');
                } else {
                    return redirect('admin/user/list')->with('status', 'Bạn cần chọn 1 thao tác');
                }
            }
            return redirect('admin/user/list')->with('status', 'Bạn không thể thao tác trên tài khoản của bạn');
        } else {
            return redirect('admin/user/list')->with('status', 'Bạn cần chọn phần tử để thực thi');
        }
    }
}
