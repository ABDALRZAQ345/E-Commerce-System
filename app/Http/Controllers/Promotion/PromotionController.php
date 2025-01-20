<?php

namespace App\Http\Controllers\Promotion;

use App\Enums\RoleEnum;
use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function index(Request $request): JsonResponse
    {

        try {
            $promotions = Promotion::orderBy('created_at', 'desc');
            if ($request->filled('accepted')) {
                $isAccepted = filter_var($request->input('accepted'), FILTER_VALIDATE_BOOLEAN);
                if ($isAccepted) {
                    $promotions->where('accepted_at', '!=', null);
                } else {
                    $promotions->where('accepted_at', '=', null);
                }
            }
            $promotions = $promotions->paginate(20);

            return response()->json([
                'status' => true,
                'message' => 'promotions retrieved successfully',
                'promotions' => $promotions,
            ], 200);
        } catch (\Exception $e) {
            throw new ServerErrorException($e);
        }
    }

    /**
     * @throws ServerErrorException
     * @throws BadRequestException
     */
    public function create(): JsonResponse
    {
        $user = Auth::user();
        if ($user->hasRole(RoleEnum::Manager)) {
            throw new BadRequestException('you already have a manager role');
        }
        if (Promotion::where('user_id', $user->id)->exists()) {
            throw new BadRequestException('Promotion request  already exists you can not send more than one request');
        }

        try {

            $promotion = Promotion::create(['user_id' => $user->id]);

            return response()->json([
                'status' => true,
                'message' => 'Promotion request created successfully',
                'data' => $promotion,
            ], 201);

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function promote(Promotion $promotion): JsonResponse
    {

        try {
            DB::beginTransaction();
            $user = User::findOrFail($promotion->user_id);

            $user->assignRole(RoleEnum::Manager);

            $promotion->accepted_at = now();

            $promotion->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User Promoted Successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function reject(Promotion $promotion): JsonResponse
    {
        try {

            $promotion->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Promotion rejected Successfully',
            ], 200);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function check(): JsonResponse
    {
        $user=Auth::user();
        return response()->json([
            'status'=>true,
            'accepted' => $user->hasRole(RoleEnum::Manager),
        ]);

    }
}
