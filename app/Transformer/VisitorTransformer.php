<?php

namespace App\Transformer;

use JWTAuth;

class VisitorTransformer {

    public function transform($visitor) {
        $jsonResponse = [
            'id' => $visitor->id,
            'document_id' => $visitor->document_id,
            'full_name' => $visitor->full_name,
            'leader_full_name' => $visitor->leader_full_name,
            'created_at' => $visitor->created_at,
            'updated_at' => $visitor->updated_at
        ];

        if ($user = JWTAuth::parseToken()->authenticate()) {
            if ($user->hasRole('admin')) {
                $jsonResponse['deleted_at'] = $visitor->deleted_at;
            }
        }

        return $jsonResponse;
    }
}
