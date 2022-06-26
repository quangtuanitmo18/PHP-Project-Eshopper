<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    //
    public $setting_statuses = [
        0 => "Chờ duyệt",
        1 => "Công Khai"
    ];
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'setting']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('status') == "trash") {
            $settings = Setting::onlyTrashed()->orderBy('created_at', 'desc')->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $settings = Setting::orderBy('created_at', 'desc')->paginate(10);
        }
        $count_setting_active = Setting::count();
        $count_setting_trash = Setting::onlyTrashed()->count();
        $count = [$count_setting_active, $count_setting_trash];
        $statuses = $this->setting_statuses;
        // return dd($users);
        return view('admin.setting.list', compact('settings', 'count', 'list_act', 'statuses'));
    }

    function add()
    {
        $statuses = $this->setting_statuses;
        return view('admin.setting.add', compact('statuses'));
    }
    function action(Request $request)
    {

        $list_check = $request->input('list_check');

        if (!empty($list_check)) {

            $act = $request->input('act');
            if ($act == 'delete') {
                Setting::destroy($list_check);
                return redirect('admin/setting/list')->with('status', 'Bạn đã xóa thành công');
            } else if ($act == 'restore') {
                Setting::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/setting/list')->with('status', 'Bạn đã khôi phục thành công');
            } else if ($act == 'forceDelete') {
                Setting::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/setting/list')->with('status', 'Bạn đã xóa vĩnh viễn thành công');
            } else {
                return redirect('admin/setting/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/slider/list')->with('status', 'Bạn cần chọn trang để thực thi');
        }
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'config_key' => 'required|string|max:255',
                'config_value' => 'required|string',
                'status' => 'required|integer',
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],

        );

        Setting::create([
            'config_key' => $request->input('config_key'),
            'config_value' => $request->input('config_value'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),


        ]);

        return redirect('admin/setting/list')->with('status', 'Bạn đã thêm thành công');
    }


    function edit($id)
    {
        $setting = Setting::find($id);
        $statuses = $this->setting_statuses;
        return view('admin.setting.edit', compact('setting', 'statuses'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'config_key' => 'required|string|max:255',
                'config_value' => 'required|string',
                'status' => 'required|integer',
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],

        );


        Setting::where('id', $id)->update([
            'config_key' => $request->input('config_key'),
            'config_value' => $request->input('config_value'),
            'status' => $request->input('status'),

        ]);

        return redirect('admin/setting/list')->with('status', 'Đã cập nhật thành công');
    }

    function delete($id)
    {
        $setting = Setting::find($id);
        $setting->delete();
        return redirect('admin/setting/list')->with('status', 'Đã xóa thành công');
    }
}
