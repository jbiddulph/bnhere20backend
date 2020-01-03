<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    //
    public function sendEmail(Request $request) {
        if(!$this->validateEmail($request->email)){
            return $this->failedResponse();
        }

        $this->send($request->email);
        return $this->successResponse();
    }

    public function send($email) {
        $token = $this->createToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    public function createToken($email) {
        $oldToken = DB::table('password_resets')->where('email', $email)->first();
        if($oldToken) {
            return $oldToken;
        }
        $token = Str::random(60);
        $this->saveToken($token,$email);
        return $token;
    }

    public function saveToken($token,$email) {
        DB::table('password_resets')->insert([
            'email'=> $email,
            'token'=> $token,
            'created_at'=> Carbon::now()
        ]);
    }

    public function validateEmail($email) {
        return !!User::where('email', $email)->first();
    }

    public function failedResponse() {
        return response()->json([
            'error' => 'Email doesn\'t exist'
            ],\Illuminate\Http\Response::HTTP_NOT_FOUND);
    }

    public function successResponse() {
        return response()->json([
            'data' => 'Reset email has sent successfull, Please check your inbox'
        ],\Illuminate\Http\Response::HTTP_OK);
    }
}
