@extends('layouts.admin')


@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm setting
        </div>
        <div class="card-body">
            <form action="{{route("admin.setting.update",$setting->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="config_key">Config_key</label>
                    <input class="form-control" type="text" name="config_key" id="config_key" value="{{$setting->config_key}}">
                    @error('config_key')
                            <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                @if($setting->type==="Text")
                    <div class="form-group">
                        <label for="config_value">Config_value</label>
                        <input class="form-control" type="text" name="config_value" id="config_value" value="{{$setting->config_value}}">
                        @error('Config_value')
                                <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                @elseif($setting->type=="Textarea")
                    <div class="form-group">
                        <label for="config_value">Config_value</label>
                        <textarea  class="form-control" id="config_value" name="config_value" cols="30" rows="5">{{$setting->config_value}}</textarea>
                        @error('config_value')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>

                @endif
        
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{$setting->status==$k?"checked":""}}>
                        <label class="form-check-label" for="{{$k}}">
                            {{$status}}
                        </label>
                    </div>
                    @endforeach
                    
                </div>

                



                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection