<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FORBIDDEN extends Exception
{
    protected $message;

    public function __construct($message = 'Forbidden')
    {
        $this->message = $message;
    }

    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        //
        return response()->json([
            'message' => $this->message,
        ], Response::HTTP_FORBIDDEN);
    }
}
