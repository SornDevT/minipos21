<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Transection;

class StoreController extends Controller
{
    //

    public function index(){

        // get all data from store table,order by id, paginate 10 items per page, return as json
       
        $sort = \Request::get('sort');
        $perpage = \Request::get('perpage');
        $search = \Request::get('search');
        $category = \Request::get('category');

        // $stores = Store::orderBy('id',$sort)
        // ->where(
        //     function($query) use ($search){
        //         $query->where('name','like','%'.$search.'%')
        //         ->orWhere('price_buy','like','%'.$search.'%');
        //     }
        // )
        // ->paginate($perpage)
        // ->toArray();

        
        //  $stores = Store::orderBy('id',$sort);
        // if($category != 'all'){
        //     $stores = $stores->where('category_id',$category);
        // }
        // $stores = $stores->where(
        //     function($query) use ($search){
        //         $query->where('name','like','%'.$search.'%')
        //         ->orWhere('price_buy','like','%'.$search.'%');
        //     }
        // )
        // ->paginate($perpage)
        // ->toArray();

        // store join category
        $stores = Store::join('categories','stores.category_id','=','categories.id')
        ->select('stores.*','categories.name as category_name')
        ->orderBy('stores.id',$sort);
        if($category != 'all'){
            $stores = $stores->where('stores.category_id',$category);
        }
        $stores = $stores->where(
            function($query) use ($search){
                $query->where('stores.name','like','%'.$search.'%')
                ->orWhere('stores.price_buy','like','%'.$search.'%');
            }
        )
        ->paginate($perpage)
        ->toArray();





        return response()->json($stores);




        // $stores = Store::all();
        // return response()->json($stores);
    }

    public function add(Request $request){
        try {

            // ກຳນົດເສັ້ນທາງການອັບໂຫຼດ
            $upload_path = "assets/img";

            if($request->file('image')){

                 // gen ຊື່ຮູບພາບໃໝ່ 
                 $new_name_img = time().".".$request->image->getClientOriginalExtension();

                 // ອັບໂຫຼດ
                 $request->image->move(public_path($upload_path),$new_name_img);

            } else {
                $new_name_img = "";
            }

           
            $store = new Store([
                'name' => $request->name,
                'category_id'=> $request->category_id,
                'image'=> $new_name_img,
                'qty'=> $request->qty,
                'price_buy'=> $request->price_buy,
                'price_sell'=> $request->price_sell,
            ]);

            $store->save();
            $product_id = $store->id;

            // add transection

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
                "tran_id" => "EXP".$number, // date(ymds).rand(1000,9999)
                "tran_type" => "expense",
                "product_id"=> $product_id,
                "qty"=> $request->qty,
                "price"=> $request->price_buy*$request->qty,
                "detail"=> "ນຳເຂົ້າສິນຄ້າ ".$request->name
            ]);
            $tran->save();

            

            $success = true;
            $message = 'ບັນທຶກຂໍ້ມູນສຳເລັດ!';


        } catch (\Illuminate\Database\QueryException $ex) {
           
            $success = false;
            $message = $ex->getMessage();

        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function edit($id){

        $store = Store::find($id);
        return $store;

    }

    public function update($id,Request $request){

        try {
           
            $store = Store::find($id);
            
            // ກຳນົດເສັ້ນທາງການອັບໂຫຼດ
            $upload_path = "assets/img";

            // ກວດວ່າມີຮູບອັບໂຫຼດມາແລ້ວຫຼືບໍ່
            if($request->file('image')){

             

                // ລຶບຮູບອັບເກົ່າ
                if($store->image){
                    if(file_exists(public_path($upload_path.'/'.$store->image))){
                        unlink(public_path($upload_path.'/'.$store->image));
                    }
                }

                // gen ຊື່ຮູບພາບໃໝ່ 
                $new_name_img = time().".".$request->image->getClientOriginalExtension();

                // ອັບໂຫຼດ
                $request->image->move(public_path($upload_path),$new_name_img);

                // ລຶບຮູບອັບໂຫຼດເເລ້ວ
                if($store->image != ""){
                    // unlink(public_path($upload_path.'/'.$store->image));

                    if(file_exists(public_path($upload_path.'/'.$store->image))){
                        unlink(public_path($upload_path.'/'.$store->image));
                        }
                    }

              //  }

                // ອັບເດດຂໍ້ມູນ 
                $store->update([
                    "name" => $request->name,
                    "category_id"=> $request->category_id,
                    "image" => $new_name_img,
                    "qty" => $request->qty,
                    "price_buy" => $request->price_buy,
                    "price_sell" => $request->price_sell
                ]);

            } else {
                    if($request->image){
                        $store->update([
                                    "name" => $request->name,
                                    // "image" => $request->image,
                                    "qty" => $request->qty,
                                    "price_buy" => $request->price_buy,
                                    "price_sell" => $request->price_sell
                                ]);
                            } else {


                                // ລຶບຮູບອັບໂຫຼດເກົ່າ
                                if($store->image != ""){
                                        if(file_exists($upload_path.'/'.$store->image)){
                                            unlink($upload_path.'/'.$store->image);
                                        }
                                    }   

                          // ອັບໂຫຼດຮູບໃໝ່
                        $store->update([
                            "name" => $request->name,
                            "category_id"=> $request->category_id,
                            "image" => "",
                            "qty" => $request->qty,
                            "price_buy" => $request->price_buy,
                            "price_sell" => $request->price_sell
                        ]);
                    }


                        
                    }
                

            $success = true;
            $message = 'ອັບເດດຂໍ້ມູນສຳເລັດ!';


        } catch (\Illuminate\Database\QueryException $ex) {
           
            $success = false;
            $message = $ex->getMessage();

        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function delete($id) 
    {
        try {
            $store = Store::find($id);
            $upload_path = "assets/img";

            if ($store) {

                // ລຶບຮູບອັບໂຫຼດເເລ້ວ
                if($store->image != ""){
                    if(file_exists($upload_path.'/'.$store->image)){
                        unlink($upload_path.'/'.$store->image);
                    }
                }

                $store->delete();
                $success = true;
                $message = 'ລຶບຂໍ້ມູນສຳເລັດ!';
            } else {
                $success = false;
                $message = 'ບໍ່ພົບຂໍ້ມູນ!';
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }
}
