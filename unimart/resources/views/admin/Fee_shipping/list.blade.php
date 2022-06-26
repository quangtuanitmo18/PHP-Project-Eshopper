@extends('layouts.admin')


@section('js')

<script>
    // $('#SubmitForm').on('submit',function(e){
    // e.preventDefault();
    // var province_id=$('#city').val();
    // 		var district_id=$('#district').val();

    //         var ward_street_id=$('#ward_street').val();
    //         var fee_shipping=$('#fee_shipping').val();




    // $.ajax({
    //   url: "{{route('admin.Fee_shipping.add')}}",
    //   type:"POST",
    //   data:{province_id:province_id,district_id:district_id,ward_street_id:ward_street_id,fee_shipping:fee_shipping,_token:_token},

    //   success:function(response){
    //     alert('thêm phí thành công');
    //   },
    //   error: function(response) {
    //     $('#name-error').text(response.responseJSON.errors.name);
    //     $('#email-error').text(response.responseJSON.errors.email);
    //     $('#mobile-number-error').text(response.responseJSON.errors.mobile_number);
    //     $('#subject-error').text(response.responseJSON.errors.subject);
    //     $('#message-error').text(response.responseJSON.errors.message);
    //    }
    //  });
    // });
    $(document).ready(function() {
        $('.choose').change(function() {
            var id = $(this).val();
            var name_id = $(this).attr('id');

            var result = "";
            if (name_id == "city") {
                result = "district";
            }
            if (name_id == "district") {
                result = "ward_street";
            }

            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                url: "{{ route('admin.Fee_shipping.select_delivery') }}",
                data: {
                    id: id,
                    name_id: name_id,
                    _token: _token
                },
                success: function(data) {
                    if (name_id == "city") {
                        $('.district').html(data['output_city']);
                        $('.ward_street').html(data['output_district']);

                    } else if (name_id = "district") {
                        $('.ward_street').html(data['output_district']);
                    }
                }

            });
        });
    });
</script>
@endsection


@section('content')

<div id="content" class="container-fluid">
    @if(session('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
    @endif
    <div class="row">

        <div class="col-12">
            <div class="card">

                <div class="card-header font-weight-bold">
                    Danh sách Menu
                </div>
                <div class="card-body">
                    <form id="SubmitForm" method="POST" action="{{url('admin/Fee_shipping/add')}}">
                        @csrf
                        @can('fee-shipping-add')
                        <div class="form-group">
                            <label for="per_parent">Tỉnh (Thành Phố)</label>
                            <select class="form-control choose city" id="city" name="city">
                                <option value="">---Chọn---</option>
                                @foreach($provinces as $province)
                                <option value="{{$province->id}}">{{$province->name}}</option>
                                @endforeach

                            </select>
                            @error('city')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="per_parent">Quận (Huyện)</label>
                            <select class="form-control choose district" id="district" name="district">
                                <option value="">---Chọn---</option>


                            </select>
                        </div>
                        <div class="form-group">
                            <label for="per_parent">Xã (phường)</label>
                            <select class="form-control ward_street" id="ward_street" name="ward_street">
                                <option value="">---Chọn---</option>


                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Phí vận chuyển ($)</label>
                            <input class="form-control" type="text" name="fee_shipping" id="fee_shipping">
                            @error('fee_shipping')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        @endcan


                        <button type="submit" name="add_fee_shipping" class="btn-add btn-primary add_fee_shipping">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách
                </div>
                <div class="card-body" style="padding-top:0; padding-bottom:0;">
                    <p style=" margin-bottom:0; color:red">*Phí vận chuyển mặc định là 10 ($)*</p>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên tỉnh (thành phố)</th>
                                <th scope="col">Tên quận (huyện)</th>
                                <th scope="col">Tên xã (phường)</th>
                                <th scope="col">Phí vận chuyển ($)</th>


                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <?php $t = 0; ?>

                        <tbody scope="col" name="list_fee_shipping">
                            @foreach($fee_shipping as $value)
                            <tr>
                                <?php $t++; ?>
                                <td scope="col"> {{$t}}</td>
                                <td scope="col"> {{$value->province->name}}</td>
                                @if(!empty($value->district->name))
                                <td scope="col"> {{$value->district->name}}</td>
                                @else
                                <td scope="col"> -</td>
                                @endif

                                @if(!empty($value->ward->name))
                                <td scope="col"> {{$value->ward->name}}</td>
                                @else
                                <td scope="col"> -</td>
                                @endif

                                <td scope="col"> {{$value->fee_shipping}}</td>
                                <td scope="col">
                                    @can('fee-shipping-delete')
                                    <a href="{{route('admin.Fee_shipping.delete',$value->id)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    @endcan
                                </td>
                            </tr>

                            @endforeach

                        </tbody>




                    </table>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection