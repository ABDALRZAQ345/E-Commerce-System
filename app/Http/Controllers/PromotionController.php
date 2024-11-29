<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\AuthRequests\PromotionRequest;
use App\Models\Promotion;
use App\Services\UserService;

class PromotionController extends Controller
{
    public function index()
    {
        try {
            $promotions = Promotion::orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => true,
                'message' => 'PromotionsList Created Successfully',
                'data' => $promotions,
            ], 200);
        } catch (\Exception $th) {
            throw new ServerErrorException($th);
        }
    }

    public function create(PromotionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $user = UserService::findUserByPhoneNumber($validatedData['phoen_number']);

            $promotion = Promotion::create([
                'user_id' => $user->id,
                'phone_number' => $validatedData['phone_number'],
                'accepted' => 0,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Promotion request created successfully',
                'data' => $promotion,
            ], 201);

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function promote(PromotionRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $user = UserService::findUserByPhoneNumber($validatedData['phoen_number']);

            $user->removeRole('user');
            $user->assignRole('manager');
            $promotion = Promotion::where('user_id', $user->id)->update(['accepted' => true]);

            return response()->json([
                'status' => true,
                'message' => 'User Promoted Successfully',
            ], 200);
        } catch (\Throwable $th) {
            throw new ServerErrorException($th);
        }
    }
}
