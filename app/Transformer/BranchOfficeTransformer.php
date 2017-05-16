<?php

namespace App\Transformer;

class BranchOfficeTransformer {

    public function transform($item) {
        return [
            'id' => $item->id,
            'name' => $item->name
        ];
    }
}
