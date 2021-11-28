<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ResetRequest;
use DB;

class ResetController extends Controller
{
    public function resetPassword(ResetRequest $request){
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

        $emailCheck = DB::table('password_resets')->where('email', $email)->first();
        $tokenCheck = $emailCheck->token;

        if(!$emailCheck){
            return response([
                'message' => 'Email not found',
            ], 401);
        }
        if($tokenCheck != $token){
            return response([
                'message' => 'Invalid Token',
            ], 401);
        }
        DB::table('users')->where('email', $email)->update(['password' => $password]);
        DB::table('password_resets')->where('email', $email)->delete();

        return response([
            'message' => 'Password Changed Successfully',
        ], 200);
    }
}
