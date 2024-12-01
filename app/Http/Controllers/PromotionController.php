<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Exceptions\ServerErrorException;
use App\Http\Requests\AuthRequests\PromotionRequest;
use App\Models\Promotion;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function index(Request $request)
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
            $promotions= $promotions->paginate();


            return response()->json([
                'status' => true,
                'data' => $promotions,
            ], 200);
        } catch (\Exception $th) {
            throw new ServerErrorException($th);
        }
    }

    public function create()
    {
        try {
            $user = Auth::user();
            if($user->hasRole(RoleEnum::Manager))
            {
                return response()->json([
                    'status' => false,
                    'message'=> 'you already have a manager role',
                ]);
            }
            if(Promotion::where('user_id', $user->id)->exists()){
                return response()->json([
                    'status' => false,
                    'message' => 'Promotion request  already exists you cant send more than one request',
                ]);
            }

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
    public function promote(Promotion $promotion)
    {

        try {
            DB::beginTransaction();
            $user = User::findOrFail($promotion->user_id);

            $user->assignRole(RoleEnum::Manager);
            $promotion->accepted_at=now();

            $promotion->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'User Promoted Successfully',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new ServerErrorException($th);
        }
    }

    public function reject(Promotion $promotion)
    {
        try {

           $promotion->delete();
            return response()->json([
                'status' => true,
                'message' => 'User Promotion rejected Successfully',
            ], 200);
        } catch (\Throwable $th) {
            throw new ServerErrorException($th);
        }
    }
}
