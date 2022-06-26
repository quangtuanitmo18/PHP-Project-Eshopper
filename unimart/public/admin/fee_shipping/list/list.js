
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
