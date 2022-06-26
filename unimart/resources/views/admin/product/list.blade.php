@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
        @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách sản phẩm</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" name="keyword" id="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlwithQuery(['status'=>'active']) }}" class="text-primary">Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'still']) }}" class="text-primary">Còn hàng<span class="text-muted">({{$count[2]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'out-of-stock']) }}" class="text-primary">Hết hàng<span class="text-muted">({{$count[3]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'in-stock']) }}" class="text-primary">Đang về hàng<span class="text-muted">({{$count[4]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'trash']) }}" class="text-primary">Vô hiệu hóa<span class="text-muted">({{$count[1]}})</span></a>

            </div>
            <form action="{{url('admin/product/action')}}">
                @can('product-action')
                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="" name='act'>
                        <option>Chọn</option>
                        @foreach($list_act as $k=>$value)
                        <option value="{{$k}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
                @endcan
                <table class="table table-striped table-checkall" id="myTable">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Thương hiệu</th>

                            <th scope="col">Ngày tạo</th>

                            <th scope="col">Trạng thái</th>
                            <th scope="col">Tình trạng</th>


                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->total()>0)
                        @php
                        $t=0;
                        @endphp


                        @foreach($products as $product)
                        @php
                        $t++;
                        @endphp
                        <tr class="">
                            <td>
                                <input type="checkbox" name="list_check[]" value="{{$product->id}}">
                            </td>
                            <td>{{$t}}</td>
                            <td><img src="{{url("$product->thumbnail")}}" alt="" class="img-fluid" style="max-width:100px; max-height:auto"></td>
                            <td><a href="#">{{$product->name}}</a></td>
                            <td>{{number_format($product->price,0,',','.')}} $</td>
                            <td>{{$product->product_cat->name}}</td>
                            <td>{{$product->product_brand->name}}</td>

                            <td>{{$product->created_at}}</td>
                            @php
                            $badge_color="";
                            if($product->browse==0){
                            $badge_color="badge-dark";
                            }
                            else if($product->browse==1){
                            $badge_color="badge-success";
                            }
                            @endphp
                            <td><span class="badge {{$badge_color}}">{{$browses[$product->browse]}}</span></td>

                            @php
                            $badge_color="";
                            if($product->status==0){
                            $badge_color="badge-success";
                            }
                            else if($product->status==1){
                            $badge_color="badge-warning";

                            }
                            else if($product->status==2){
                            $badge_color="badge-dark";
                            }
                            @endphp
                            <td><span class="badge {{$badge_color}}">{{$statuses[$product->status]}}</span></td>
                            <td>
                                @if(request()->input('status')!='trash')
                                @can('product-edit')
                                <a href="{{route('admin.product.edit',$product->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('product-delete')
                                <a href="{{route('admin.product.delete',$product->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" onclick="return confirm('bạn có chắc chắn muốn xóa sản phẩm này không?')" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                @endcan
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        @else
                        <tr>
                            <td colspan="7" class="bg-white">
                                không tìm thấy bản ghi
                            </td>
                        </tr>

                        @endif



                    </tbody>
                </table>
            </form>

            {{$products->links()}}
            <form action="{{route('admin.product.cat.excel_import_product')}}" method="post" enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="file" accept=".xlsx" name="import_excel_product" id="import_excel_product"><br>
                <input type="submit" value="import_csv" name="import_CSV" class="btn btn-warning mt-1">

            </form>

            <form action="{{route('admin.product.cat.excel_export_product')}}" method="post" class="mt-3">
                @csrf
                <input type="submit" value="export_csv" name="export_CSV" class="btn btn-success">

            </form>
        </div>
    </div>
</div>
@endsection