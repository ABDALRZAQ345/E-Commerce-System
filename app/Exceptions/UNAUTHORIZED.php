<?php

namespace App\Exceptions;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UNAUTHORIZED extends Exception
{
    public function __construct(string $message = 'UNAUTHORIZED')
    {
        parent::__construct($message);
    }

    public function render(Request $request): JsonResponse
    {
        //
        return response()->json([
            'status' => false,
            'error' => $this->message,
        ], Response::HTTP_UNAUTHORIZED);
    }
}
