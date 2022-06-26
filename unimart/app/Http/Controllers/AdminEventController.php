<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    //
    public $event_statuses = [

        0 => 'Chờ duyệt',
        1 => 'Công khai',

        // như đã quy ước trong database
    ];
    function list()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        $statuses = $this->event_statuses;
        return view('admin.event.list', compact('events', 'statuses'));
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
                'name' => 'Tên menu',
            ]
        );

        Event::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'status' => $request->input('status'),
        ]);
        return  redirect("admin/event/list")->with('status', 'Thêm sự kiện thành công');
    }

    function delete($id)
    {
        $event = Event::find($id);

        $event->delete();
        return redirect('admin/event/list')->with('status', 'Đã xóa sự kiện thành công');
    }
    function edit($id)
    {
        $event = Event::find($id);
        $events = Event::all();
        $statuses = $this->event_statuses;
        return view('admin.event.edit', compact('event', 'events', 'statuses'));
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
                'name' => 'Tên menu',
            ]
        );


        Event::where('id', $id)->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'status' => $request->input('status'),

        ]);
        return redirect('admin/event/list')->with('status', 'Đã cập nhật sự kiện thành công');
    }
}
