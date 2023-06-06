<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use GraphQL\Error\Error;

final class Register
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = \App\Models\User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => bcrypt($args['password'])
        ]);

        Auth::login($user);

        $token = $user->createToken('authToken')->plainTextToken;

        $token = explode('|', $token)[1];

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
