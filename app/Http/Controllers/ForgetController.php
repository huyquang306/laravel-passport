<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ForgetRequest;
use Mail;
use App\Mail\ForgetMail;

class ForgetController extends Controller
{
    public function forgetPassword(ForgetRequest $request){
        $email = $request->email;
        if(User::where('email', $email)->doesntExist()){
            return response([
                'message' => 'Invalid Email',
            ], 401);
        }

        //generate random token
        $token = rand(100000, 999999);
        try{
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
            ]);

            //Send mail to user
            Mail::to($email)->send(new ForgetMail($token));

            return response([
                'message' => 'Reset Password Mail is sent to your email',
            ], 200);

        }catch(Exception $exception){
            return response([
                'message' => $e->getMassage()
            ], 400);
        }
    }
}
