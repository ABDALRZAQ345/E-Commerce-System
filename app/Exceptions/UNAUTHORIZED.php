<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class UNAUTHORIZED extends Exception
{
    public function __construct(string $message = 'UNAUTHORIZED')
    {
        parent::__construct($message);
    }

    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        //
        return response()->json([
            'message' => $this->message,
        ], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
    }
}
