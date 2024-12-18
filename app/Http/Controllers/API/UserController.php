<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use JWTAuth;
use Illuminate\Support\Facades\Auth;

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

    public function login(Request $request){

        try {

                $user_login = [
                    'email' => $request->login_email,
                    'password' => $request->login_password
                ];

                // ກວດເພີ່ມເວລາໃຫ້ token
                if($request->login_remember_me == 'true'){
                    JWTAuth::factory()->setTTL(10080); // 60 = 1h, 1month, 10080 = 7 day
                }

                // ກວດຊອບ email & password
                $token = JWTAuth::attempt($user_login);
                $user_data = Auth::user(); // ເກັບຂໍ້ມູນ user ທີ່ທຳການ login ຜ່ານ


                if($token){
                    $success = true;
                    $message = 'ເຂົ້າສູ່ລະບົບສຳເລັດ!';
                } else {
                    $success = false;
                    $message = 'ອີເມວລ໌ ຫຼື ລະຫັດຜ່ານບໍ່ຖຶກຕ້ອງ!';
                }

                
    
    
            } catch (\Illuminate\Database\QueryException $ex) {
               
                $success = false;
                $message = $ex->getMessage();
                $user_data = '';
                $token = '';
    
            }
    
            $response = [
                'success' => $success,
                'message' => $message,
                'user_data' => $user_data,
                'token' => $token
            ];
    
            return response()->json($response);


        

        // if($token){

        //     $response = [
        //         'success' => true,
        //         'message' => 'ສຳເລັດ',
        //         'user_data' => $user_data,
        //         'token' => $token
        //     ];
    
        //     return response()->json($response);

        // } else {

        //     $response = [
        //         'success' => false,
        //         'message' => 'ອີເມວລ໌ ຫຼື ລະຫັດຜ່ານບໍ່ຖຶກຕ້ອງ!'
        //     ];
    
        //     return response()->json($response);

        //}


    }


    // ບໍ່ນຳໃຊ້ try catch
    // public function login(Request $request){

    //     $user_login = [
    //         'email' => $request->login_email,
    //         'password' => $request->login_password
    //     ];

    //     // ກວດເພີ່ມເວລາໃຫ້ token
    //     if($request->login_remember_me == 'true'){
    //         JWTAuth::factory()->setTTL(10080); // 60 = 1h, 1month, 10080 = 7 day
    //     }

    //     // ກວດຊອບ email & password
    //     $token = JWTAuth::attempt($user_login);
    //     $user_data = Auth::user(); // ເກັບຂໍ້ມູນ user ທີ່ທຳການ login ຜ່ານ


    //     if($token){

    //         $response = [
    //             'success' => true,
    //             'message' => 'ສຳເລັດ',
    //             'user_data' => $user_data,
    //             'token' => $token
    //         ];
    
    //         return response()->json($response);

    //     } else {

    //         $response = [
    //             'success' => false,
    //             'message' => 'ອີເມວລ໌ ຫຼື ລະຫັດຜ່ານບໍ່ຖຶກຕ້ອງ!'
    //         ];
    
    //         return response()->json($response);

    //     }


    // }


    public function logout(){
        $token = JWTAuth::getToken();
        $invalidate = JWTAuth::invalidate($token);

        if($invalidate){
            $response = [
                'success' => true,
                'message' => 'ອອກຈາກລະບົບສຳເລັດ!'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'ອກກຈາກລະບົບ ບໍ່ສຳເລັດ!'
            ];
        }

        return response()->json($response);
    }

    public function testjwt(){
        return 'test jwt';
    }
}
