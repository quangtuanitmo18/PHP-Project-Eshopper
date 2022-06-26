@extends('layouts.admin')

@section('css')
<link href="{{asset('vendors/select2/select2.min.css')}}" rel="stylesheet" />




@endsection

@section('js')
<script src="{{asset('vendors/select2/select2.min.js')}}"></script>



<script>
    $(document).ready(function() {
        $(".tags_select2").select2({
            tags: true,
            tokenSeparators: [',']
        })
    });
</script>
@endsection

@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm sản phẩm
        </div>
        <div class="card-body">
            <form action="{{url("admin/product/store")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Tên sản phẩm</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="name">Giá nhập</label>
                                    <input class="form-control" type="text" name="price_cost" id="price_cost" value="{{old('price_cost')}}">
                                    @error('price_cost')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="name">Giá bán</label>
                                    <input class="form-control" type="text" name="price" id="price" value="{{old('price')}}">
                                    @error('price')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="name">Số lượng</label>
                                    <input class="form-control" type="text" name="qty" id="qty" value={{old('qty')}}>
                                    @error('qty')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="intro">Mô tả sản phẩm</label>
                            <input class="form-control" type='text' id="description" name="description" value="{{old('description')}}">
                            @error('description')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="intro">Chi tiết sản phẩm</label>
                    <textarea class="form-control" id="content" name="content" cols="30" rows="5">{{old('content')}}</textarea>
                    @error('content')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select class="form-control" id="product_cat_id" name="product_cat_id">
                        <option value="">Chọn danh mục</option>
                        @php
                        $cats_tmp=array();
                        showCategories($product_cats,$cats_tmp);

                        @endphp
                        @foreach($cats_tmp as $k=>$value)
                        <option value="{{$k}}" {{old('product_cat_id')==$k?"selected='selected'":""}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('product_cat_id')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="">Thương hiệu</label>
                    <select class="form-control" id="product_brand" name="product_brand">
                        <option value="">Chọn danh mục</option>
                        @foreach($brands as $value)
                        <option value="{{$value->id}}" {{old('product_brand')==$k?"selected='selected'":""}}>{{$value->name}}</option>
                        @endforeach
                    </select>

                    @error('product_brand')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Nhập tags</label>
                    <select class="form-control tags_select2" multiple="multiple" name="product_tags[]">
                        @foreach ($tags as $tag)
                        <option value="{{$tag->name}}">{{$tag->name}}</option>
                        @endforeach

                    </select>
                </div>




                <div class="form-group">
                    <label for="thumbnail">Chọn hình ảnh</label>
                    <input type="file" class="form-control-file" name="thumbnail" id="thumbnail">
                    @error('thumbnail')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">



                    <label for="thumbnail_detail">Chọn hình ảnh chi tiết</label>
                    <input type="file" class="form-control-file" name="thumbnail_detail[]" id="thumbnail_detail" multiple>
                    @error('thumbnail_detail')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Sản phẩm</label><br>

                    <div class="form-check">

                        <input class="form-check-input" type="radio" name="featured" id="featured">
                        <label class="form-check-label" for="featured">Nổi bật</label><br>
                        <input class="form-check-input" type="radio" name="best_seller" id="best_seller">
                        <label class="form-check-label" for="best_seller"> Bán chạy</label><br>
                    </div>
                </div>


                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($browses as $k=>$browse)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="browse" id="{{$k}}_browse" value="{{$k}}" {{old('browse')==$k?"checked":""}}>
                        <label class="form-check-label" for="{{$k}}_browse">
                            {{$browse}}
                        </label>
                    </div>
                    @endforeach

                </div>
                <div class="form-group">
                    <label for="">Tình trạng</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}_status" value="{{$k}}" {{old('status')==$k?"checked":""}}>
                        <label class="form-check-label" for="{{$k}}_status">
                            {{$status}}
                        </label>
                    </div>
                    @endforeach

                </div>



                <button type="submit" name="btn-add" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection