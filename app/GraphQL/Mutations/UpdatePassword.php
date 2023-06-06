<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use GraphQL\Error\Error;

final class UpdatePassword
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::user();

        if (!Auth::attempt(['email' => $user->email, 'password' => $args['old_password']])) {
            throw new Error('Invalid credentials. Please make sure your old password is correct.');
        }

        $user->password = bcrypt($args['new_password']);
        $user->save();

        return $user;
    }
}
