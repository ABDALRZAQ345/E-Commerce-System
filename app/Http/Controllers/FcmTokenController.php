<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    //
    public function send(Request $request)
    {
        try {
            $request->validate([
                'fcm_token' => ['required', 'string'],
            ]);
            $user = Auth::user();
            $user->fcm_token = $request->fcm_token;

            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
            ]);
        }

    }
}
