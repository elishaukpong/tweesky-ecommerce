<?php

namespace App\Service;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthenticationService
{

    public function __construct(protected UserRepository $userRepository)
    {
        //
    }

    public function register(array $attributes): User
    {
        return DB::transaction(function () use ($attributes) {
            $user = $this->userRepository->create($attributes);

            $user->token = $user->createToken(config('app.name'))->plainTextToken;

            return $user;
        });
    }

    public function authenticate(array $attributes): User
    {
        if (! Auth::attempt($attributes)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $user = $this->userRepository->first('email', $attributes['email']);
        $user->token = $user->createToken('betawork')->plainTextToken;

        return $user;
    }
}
