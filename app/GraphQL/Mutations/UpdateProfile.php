<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

final class UpdateProfile
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::user();
        $user->name = $args['name'];
        if ($user->email !== $args['email']) {
            $user->tokens()->delete();
        }
        $user->save();
        return $user;
    }
}
