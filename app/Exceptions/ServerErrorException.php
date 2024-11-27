<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerErrorException extends Exception
{
    public function __construct(string $message = 'Server Error')
    {
        parent::__construct($message);
    }
    public function render(Request $request)
    {
        return response()->json([
            'message' => $this->message,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
