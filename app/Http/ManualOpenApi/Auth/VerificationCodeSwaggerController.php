<?php

namespace App\Http\ManualOpenApi\Auth;

use OpenApi\Annotations as OA;

class VerificationCodeSwaggerController
{
    /**
     * @OA\Post(
     *     path="/api/verificationCode/send",
     *     tags={"Verification"},
     *     summary="Send a verification code to the phone number",
     *     description="Sends a verification code to the provided phone number either for registration or password recovery.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"phone_number"},
     *
     *             @OA\Property(
     *                 property="phone_number",
     *                 type="string",
     *                 example="0947777777",
     *                 description="The phone number to which the verification code will be sent. Must start with '09' and be 10 digits long."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Verification code sent successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true,
     *                 description="Indicates whether the request was successful."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Verification code sent successfully to 0947777777",
     *                 description="The success message."
     *             )
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
     *                         example="The phone number must start with '09' and be exactly 10 digits."
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function Send() {}

    /**
     * @OA\Post(
     *     path="/api/verificationCode/check",
     *     tags={"Verification"},
     *     summary="Check if the verification code is valid",
     *     description="Validates the phone number and the verification code.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"phone_number", "code"},
     *
     *             @OA\Property(
     *                 property="phone_number",
     *                 type="string",
     *                 example="0947777777",
     *                 description="The phone number to verify, must start with '09' and be 10 digits long."
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="123456",
     *                 description="The verification code consisting of exactly 6 digits."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Verification code is valid.",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true,
     *                 description="Indicates whether the request was successful."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Verification code is valid.",
     *                 description="The success message."
     *             )
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
     *                     property="code",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         example="The verification code must be exactly 6 digits."
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function check() {}
}
