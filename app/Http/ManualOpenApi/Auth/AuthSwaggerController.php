<?php

namespace App\Http\ManualOpenApi\Auth;

use OpenApi\Annotations as OA;

class AuthSwaggerController
{
    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Registers a new user by validating the phone number, code, and user details.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"first_name", "password", "phone_number", "code"},
     *
     *             @OA\Property(property="first_name", type="string", maxLength=50, description="The user's first name"),
     *             @OA\Property(property="last_name", type="string", maxLength=50, description="The user's last name"),
     *             @OA\Property(property="password", type="string", description="The user's password"),
     *         @OA\Property(property="password_confirmation", type="string", description="The user's password confirmation"),
     *             @OA\Property(property="phone_number", type="string", description="The user's phone number"),
     *             @OA\Property(property="photo", type="string", format="binary", description="Profile photo (optional)"),
     *             @OA\Property(property="code", type="string", description="Verification code sent to the user", maxLength=6)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Created Successfully"),
     *             @OA\Property(property="token", type="string", example="sample-sanctum-token"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=422,
     *          description="Validation error",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="phone_number",
     *                      type="array",
     *
     *                      @OA\Items(
     *                          type="string",
     *                          example="The phone number field is required."
     *                      )
     *                  ),
     *
     *                  @OA\Property(
     *                      property="code",
     *                      type="array",
     *
     *                      @OA\Items(
     *                          type="string",
     *                          example="The verification code must be exactly 6 digits."
     *                      )
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Logs in a user by validating the phone number and password.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"phone_number", "password"},
     *
     *             @OA\Property(property="phone_number", type="string", description="The user's phone number"),
     *             @OA\Property(property="password", type="string", description="The user's password")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in successfully"),
     *             @OA\Property(property="token", type="string", example="sample-sanctum-token"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid phone number or password",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Phone & Password do not match our record.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         example="The phone number field is required."
     *                     )
     *                 ),
     *
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         example="The password field is required."
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     )
     * )
     */
    public function login() {}

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Auth"},
     *     summary="User logout",
     *     description="Logs out the user by deleting their active Sanctum token.",
     *    security={{"bearerAuth": {} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - No active token found or invalid token",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     )
     * )
     */
    public function logout() {}
}
