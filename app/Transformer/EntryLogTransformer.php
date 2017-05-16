<?php

namespace App\Transformer;

use JWTAuth;

class EntryLogTransformer {

    public function transform($entryLog) {
        $jsonResponse = [
            'id' => $entryLog->id,
            'entry_id' => $entryLog->entry_id,
            'performed_by' => $entryLog->performed_by,
            'performed_at' => $entryLog->performed_at,
            'requested_data' => $entryLog->requested_data,
        ];

        return $jsonResponse;
    }
}
