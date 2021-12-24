<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
          
            $response = [
                'message' => 'Already Verified',
            ];
            return response($response, 200);
        }

        $request->user()->sendEmailVerificationNotification();

        $response = [
            'status' => 'verification-link-sent',
        ];
        return response($response, 200);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            $response = [
                'message' => 'Emaiil Already Verified',
            ];
            return response($response, 201);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $response = [
            'message'=>'Email has been verified',
        ];
        return response($response, 200);
    }
}
