<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //

    public function register(Request $request){

        try {
           
            $user = new User([
                'name' => $request->form_user_name,
                'email' => $request->form_user_email,
                'password' => $request->form_password,
            ]);
            $user->save();

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
}
