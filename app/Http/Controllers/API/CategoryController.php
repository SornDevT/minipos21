<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //
    // ceate crud for category, index, add, edit, update, delete using try catch

    public function index(){
        // get all data from category table,order by id, return as json
        $categories = Category::orderBy('id','desc')->get();
        return response()->json($categories);
    }

    public function add(Request $request){
        try {
            $category = new Category([
                'name' => $request->name,
            ]);
            $category->save();

            $success = true;
            $message = 'ບັນທຶກຂໍ້ມູນ ສຳເລັດ!';

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
        // get data by id from category table, return as json
        $category = Category::find($id);
        return response()->json($category);
    }

    public function update($id,Request $request){
        try {
            $category = Category::find($id);
            $category->name = $request->name;
            $category->save();

            $success = true;
            $message = 'ປັບປຸງຂໍ້ມູນ ສຳເລັດ!';

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

    public function delete($id){
        try {
            $category = Category::find($id);
            $category->delete();

            $success = true;
            $message = 'ລົບຂໍ້ມູນ ສຳເລັດ!';

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
