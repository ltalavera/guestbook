<?php

namespace App\Transformer;

class GuestTypeTransformer {

    public function transform($item) {
        return [
            'id' => $item->id,
            'name' => $item->name
        ];
    }
}
