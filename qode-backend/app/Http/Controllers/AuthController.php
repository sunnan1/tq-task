<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Jobs\UserRegistrationJob;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request)
    {
        try {
            $otp = rand(100000, 999999);
            if ($user = User::whereEmail($request->email)->first()) {
                $user->otp = $otp;
                $user->save();
            } else {
                $user = User::create([
                    'email' => $request->email,
                    'name' => $request->name,
                    'otp' => $otp,
                ]);
            }
            UserRegistrationJob::dispatch($user);
            return response()->json(['message' => 'OTP sent, please check your email'] , 200);
        }catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()] , 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user->updated_at->addMinutes(2)->isPast()) {
                return response()->json(['message' => 'OTP Expired'], 401);
            }
            if ($user->otp == $request->otp) {
                $user->otp = null;
                if (empty($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }
                $user->save();
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json(['token' => $token , 'message' => 'Logged In Successfully'] , 200);
            }
            return response()->json(['message' => 'Invalid OTP'], 401);
        }catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

