<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Service\AuthenticationService;

class LoginController extends Controller
{
    public function __construct(protected AuthenticationService $authenticationService)
    {
        //
    }

    public function __invoke(LoginRequest $request)
    {
        $user = $this->authenticationService->authenticate($request->validated());

        return $this->success(__('User Logged In'),UserResource::make($user));
    }
}
