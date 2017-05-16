<?php

namespace App\Transformer;

class UserTransformer {

    public function transform($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }
}