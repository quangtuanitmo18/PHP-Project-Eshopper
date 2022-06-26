<?php

namespace App\Http\Controllers;

use App\Mail\check_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    //
 
    function sendmail(){
        $data=[
            'title'=>'dasd'
        ];
        Mail::to('quangtuanitmo18@gmail.com')->send(new check_order($data));
    }

}
