<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\SignupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {

    }

    public function register(SignupRequest $request)
    {

    }

    public function logout(Request $request)
    {


    }


    public function update(Request $request)
    {

    }
}
