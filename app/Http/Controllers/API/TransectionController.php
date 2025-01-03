<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transection;

use App\Models\Bill;
use App\Models\Bill_list;
use App\Models\Store;

class TransectionController extends Controller
{
    //
    public function add(Request $request){
        // use try catch to handle the error
        try {


             // --- ສ້າງຂໍ້ມູນໃບບິນ
                // ບັນທຶກ ຂໍ້ມູນໃບບິນ
                $bill_id='';
                $read_bill = Bill::all()->sortByDesc('id')->take(1)->toArray();
                foreach($read_bill as $new){
                    $bill_id = $new['bill_id'];
                }

                if($bill_id!=''){
                    $bill_id = (int)$bill_id+1; // 1+1 = 2
                    $length = 5;
                    $bill_id = substr(str_repeat(0,$length).$bill_id, - $length); //00002
                } else {
                    $bill_id = 1;
                    $length = 5;
                    $bill_id = substr(str_repeat(0,$length).$bill_id, - $length); //00001
                }

                $bill = new Bill([
                    "bill_id" => $bill_id,
                    "customer_name" => $request->customer_name,
                    "customer_tel" => $request->customer_tel,
                ]);
                $bill->save();
            // -----------


            // foreach listorder from request
            foreach($request->listorder as $item){

                // generate tran_id
                // $tran_id = 'TRN'.date('YmdHis').rand(1000,9999);

                $number='';
                $read_tran = Transection::all()->sortByDesc('id')->take(1)->toArray();
                foreach($read_tran as $new){
                    $number = $new['tran_id'];
                }
    
                if($number!=''){
                    $number1 = str_replace("INC","",$number); // INC00001 = 00001 
                    $number2 = str_replace("EXP","",$number1);
                    $number3 = (int)$number2+1; // 1+1 = 2
                    $length = 5;
                    $number = substr(str_repeat(0,$length).$number3, - $length); //00002
                } else {
                    $number3 = 1;
                    $length = 5;
                    $number = substr(str_repeat(0,$length).$number3, - $length); //00001
                }

                // create new transection
                $tran = new Transection([
                    "tran_id" => "INC".$number, // date(ymds).rand(1000,9999)
                    "tran_type" => "income",
                    "product_id"=> $item["id"],
                    "qty"=> $item["qty"],
                    "price"=> $item["price"]*$item["qty"],
                    "detail"=> $item["name"]
                ]);
                $tran->save();


                /// ບັນທຶກລາຍການ ໃບບິນ

                $bill_list = new Bill_list([
                    "bill_id" => $bill_id,
                    "name" => $item["name"],
                    "qty" => $item["qty"],
                    "price" => $item["price"]
                ]);
                $bill_list->save();


                 /// ຕັດສະຕ໋ອກ
                 $store = Store::find($item["id"]);
                 $store_update = Store::find($item["id"]);

                 $store_update->update([
                     "qty" => $store->qty - $item["qty"]
                 ]);

            
            }
           
          
            $success = true;
            $message = 'ບັນທຶກຂໍ້ມູນສຳເລັດ!';


        } catch (\Illuminate\Database\QueryException $ex) {
           
            $success = false;
            $message = $ex->getMessage();
            $bill_id = '';

        }

        $response = [
            'success' => $success,
            'message' => $message,
            'bill_id' => $bill_id
        ];

        return response()->json($response);
    
    }

    public function index(){
        
        $sort = \Request::get('sort');
        $per_page = \Request::get('per_page');
        $month_type = \Request::get('month_type');
        $dmy = \Request::get('dmy'); // 2025-01-01

     

        $m = explode("-",$dmy)[1];
        $y = explode("-",$dmy)[0];

        // get all transection
        if($month_type == "m"){

            $tran = Transection::orderBy("id",$sort)
            ->whereYear("created_at",$y) 
            ->whereMonth("created_at",$m)
            ->paginate($per_page)
            ->toArray();

           

        } else if($month_type == "y"){

            $tran = Transection::orderBy("id",$sort)
            ->whereYear("created_at",$y)
            ->paginate($per_page)
            ->toArray();

        }

        return array_reverse($tran);

    }
}
