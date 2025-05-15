<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Service\AuthenticationService;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{

    public function __construct(protected AuthenticationService $authenticationService)
    {
        //
    }

    public function __invoke(RegistrationRequest $request): JsonResponse
    {
        $user = $this->authenticationService->register($request->validated());

        return $this->success(__('User Registered'),UserResource::make($user));
    }
}
