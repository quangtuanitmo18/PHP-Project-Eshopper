<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment_VNpay extends Model
{
    //
    protected $table = "payment_vnpay";
    protected $fillable = [
        'order_id', 'customer_id', 'note', 'money', 'order_code', 'vnp_response_code', 'code_vnpay', 'code_bank', 'transfer_time',
    ];
}
