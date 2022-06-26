@extends('layouts.admin')


@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Chỉnh sửa mã
        </div>
        <div class="card-body">
            <form action="{{url("admin/order/coupon/store")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Mã</label>
                            <input class="form-control" type="text" name="code" id="code" value="{{old('code')}}" >
                            @error('code')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>                  
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Sự kiện</label>
                            
                            <select class="form-control" id="event_id" name="event_id" >
                                <option value="">Chọn sự kiện</option>
                                @foreach($events as $event)
                                    <option value="{{$event->id}}" {{old('event_id')==$event->id?"selected='selected'":""}} >{{$event->name}}</option>
                               @endforeach
                            </select>
                            @error('event_id')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="qty">Số lượng</label>
                            <input class="form-control" type="text" name="qty" id="qty" value={{old('qty')}}>
                            @error('qty')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Điều kiện</label>
                            
                            <select class="form-control" id="condition" name="condition" >
                               <option value="">Chọn điều kiện</option>
                                @foreach($conditions as $k=>$value)
                                    <option value="{{$k}}" {{old('condition')==$k?"selected='selected'":""}}>{{$value}}</option>
                               @endforeach
                            </select>
                            @error('condition')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="value">Giá trị</label>
                            <input class="form-control" type="text" name="value" id="value" value={{old('value')}}>
                            @error('value')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>  
                    </div>
                </div>
               
                <div class="form-group">
                    <label for="date_end">Ngày hết hạn</label>
                    <input type="datetime-local" name="date_end" id="date_end" value="{{ old('date_end') }}">
                    @error('date_end')
                    <small class="text-danger">{{$message}}</small>
                     @enderror
                </div>
                
               
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{old('status')==$k?"checked":""}}>
                        <label class="form-check-label" for="{{$k}}">
                            {{$status}}
                        </label>
                    </div>
                    @endforeach
                    
                </div>



                <button type="submit" name="btn-update" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection