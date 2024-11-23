<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends Exception
{
    public function __construct(string $message = 'Object Not found')
    {
        parent::__construct($message);
    }

    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        //
        return response()->json([
            'message' => $this->message,
        ], Response::HTTP_NOT_FOUND);
    }
}
