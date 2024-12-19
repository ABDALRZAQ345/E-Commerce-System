<?php

namespace App\Http\ManualOpenApi\Auth;

use OpenApi\Annotations as OA;

class PasswordSwaggerController
{
    /**
     * @OA\Post(
     *     path="/password/forget",
     *     tags={"Auth"},
     *     summary="Reset user password",
     *     description="Allows a user to reset their password by providing their phone number, verification code, and new password",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"phone_number", "password", "password_confirmation", "code"},
     *
     *             @OA\Property(
     *                 property="phone_number",
     *                 type="string",
     *                 example="1234567890",
     *                 description="User's phone number."
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="newPassword123!",
     *                 description="New password for the user."
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 format="password",
     *                 example="newPassword123!",
     *                 description="Confirmation for the new password."
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="123456",
     *                 description="6-digit verification code sent to the user's phone."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Password changed successfully!"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "phone_number": {"The phone number field is required."},
     *                     "password": {"The password field must be at least 8 characters."},
     *                     "code": {"The code field must be a valid 6-digit number."}
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid or expired verification code.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid code"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="General error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="An unexpected error occurred."
     *             )
     *         )
     *     )
     * )
     */
    public function forget() {}

    /**
     * @OA\Post(
     *     path="/password/reset",
     *     summary="Reset user password",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"old_password", "new_password"},
     *
     *             @OA\Property(property="old_password", type="string", description="The current password of the user"),
     *             @OA\Property(property="new_password", type="string", description="The new password to set for the user")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Password reset successfully!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Incorrect old password",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Wrong old password!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity - Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="old_password",
     *                     type="array",
     *                     items=@OA\Items(type="string"),
     *                     description="Validation errors for the old_password field"
     *                 ),
     *                 @OA\Property(
     *                     property="new_password",
     *                     type="array",
     *                     items=@OA\Items(type="string"),
     *                     description="Validation errors for the new_password field"
     *                 )
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function reset() {}
}
