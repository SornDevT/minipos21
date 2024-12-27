<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Bill_list;

class BillController extends Controller
{
    //

    public function print_bill($id){

        $bill = Bill::where('bill_id',$id)->get();
        $bill_list = Bill_list::where('bill_id',$id)->get();

       return view('bill',compact('id','bill','bill_list'));
    }
}
