<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use GraphQL\Error\Error;

final class Login
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     * @return array
     * @throws Error
     */
    public function __invoke($_, array $args)
    {
        $credentials = [
            'email' => $args['email'],
            'password' => $args['password']
        ];

        if (!Auth::attempt($credentials)) {
            throw new Error('Invalid credentials. Please make sure your email and password are correct.');
        }

        $user = Auth::user();

        $token = $user->createToken('authToken')->plainTextToken;

        $token = explode('|', $token)[1];

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
