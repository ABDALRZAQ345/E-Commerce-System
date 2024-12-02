<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Exceptions\ServerErrorException;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class PromotionController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    /**
     * @OA\Get(
     *     path="/promotions",
     *     tags={"Promotions"},
     *     summary="List all promotion requests",
     *     description="Retrieve a paginated list of promotion requests. Only accessible to admins.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="accepted",
     *         in="query",
     *         description="Filter promotions by their acceptance status. Use 'true' for accepted requests and 'false' for pending requests.",
     *         required=false,
     *
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of promotion requests retrieved successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *
     *                     @OA\Items(
     *
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="user_id", type="integer", example=5),
     *                         @OA\Property(property="accepted_at", type="string", format="datetime", nullable=true, example="2024-12-01 12:00:00"),
     *                         @OA\Property(property="created_at", type="string", format="datetime", example="2024-12-01 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="datetime", example="2024-12-01 11:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=20),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="last_page", type="integer", example=2)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden. User does not have the required admin role.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You do not have permission to perform this action."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An unexpected error occurred."
     *             )
     *         )
     *     )
     * )
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
            $promotions = $promotions->paginate();

            return response()->json([
                'status' => true,
                'data' => $promotions,
            ], 200);
        } catch (\Exception $th) {
            throw new ServerErrorException($th);
        }
    }

    /**
     * @OA\Post(
     *     path="/promotions/create",
     *     tags={"Promotions"},
     *     summary="Create a promotion request",
     *     description="Allows a user to send a promotion request to become a manager. Users can only send one request, and users with an existing manager role cannot send a request.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=201,
     *         description="Promotion request created successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Promotion request created successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     example=123
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     format="datetime",
     *                     example="2024-12-01T12:34:56.000000Z"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     format="datetime",
     *                     example="2024-12-01T12:34:56.000000Z"
     *                 ),
     *                  @OA\Property(
     *                      property="accepted_at",
     *                      type="string",
     *                      format="datetime",
     *                      example="2024-12-01T12:34:56.000000Z"
     *                  )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request due to one of the following reasons: the user already has a manager role, or a promotion request already exists.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="you already have a manager role"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Server error message"
     *             )
     *         )
     *     )
     * )
     */
    public function create()
    {
        try {
            $user = Auth::user();
            if ($user->hasRole(RoleEnum::Manager)) {
                return response()->json([
                    'status' => false,
                    'message' => 'you already have a manager role',
                ], 400);
            }
            if (Promotion::where('user_id', $user->id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Promotion request  already exists you cant send more than one request',
                ], 400);
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
    /**
     * @OA\Post(
     *     path="/promotions/{promotion}/accept",
     *     tags={"Promotions"},
     *     summary="Promote a user to a Manager role",
     *     description="Allows an authorized admin to promote a user by accepting their promotion request.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="promotion",
     *         in="path",
     *         required=true,
     *         description="ID of the promotion request",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User promoted successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User Promoted Successfully"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden. User does not have the required admin role.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You do not have permission to perform this action."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Promotion request not found.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Promotion request not found."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An unexpected error occurred."
     *             )
     *         )
     *     )
     * )
     */
    public function promote(Promotion $promotion)
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
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new ServerErrorException($th);
        }
    }

    /**
     * @OA\Post(
     *     path="/promotions/{promotion}/reject",
     *     tags={"Promotions"},
     *     summary="Reject a promotion request",
     *     description="Rejects a promotion request by deleting it. Only accessible to admins.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="promotion",
     *         in="path",
     *         required=true,
     *         description="ID of the promotion request to be rejected",
     *
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Promotion request rejected successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Promotion rejected Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Promotion request not found.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Promotion not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden. User does not have the required admin role.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You do not have permission to perform this action.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */
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
