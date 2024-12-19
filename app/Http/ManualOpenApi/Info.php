<?php

namespace App\Http\ManualOpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="one piece 3mk",
 *     version="1.0.0",
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Localhost API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum",
 *     description="Use 'Bearer <token>' in the Authorization header"
 * )
 */
class Info {}
