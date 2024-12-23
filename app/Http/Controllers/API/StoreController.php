<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    //

    public function index(){

        // get all data from store table,order by id, paginate 10 items per page, return as json
       
        $sort = \Request::get('sort');
        $perpage = \Request::get('perpage');
        $search = \Request::get('search');

        $stores = Store::orderBy('id',$sort)
        ->where(
            function($query) use ($search){
                $query->where('name','like','%'.$search.'%')
                ->orWhere('price_buy','like','%'.$search.'%');
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
                'image'=> $new_name_img,
                'qty'=> $request->qty,
                'price_buy'=> $request->price_buy,
                'price_sell'=> $request->price_sell,
            ]);

            $store->save();

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
            
            // ອັບເດດຂໍ້ມູນມູນ
            $store->update([
                "name" => $request->name,
                // "image" => ,
                "qty" => $request->qty,
                "price_buy" => $request->price_buy,
                "price_sell" => $request->price_sell
            ]);

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

            if ($store) {
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
