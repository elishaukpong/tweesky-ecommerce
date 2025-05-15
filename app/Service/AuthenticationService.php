<?php

namespace App\Service;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class AuthenticationService
{

    public function __construct(protected UserRepository $userRepository)
    {
        //
    }

    public function register(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $user = $this->userRepository->create($attributes);

            $user->token = $user->createToken(config('app.name'))->plainTextToken;

            return $user;
        });
    }

    public function login()
    {

    }
}
