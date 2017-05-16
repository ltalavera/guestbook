<?php

namespace App\Transformer;

use JWTAuth;

class EntryTransformer {

    public function transform($entry) {
        $jsonResponse = [
            'id' => $entry->id,
            'branch_office' => $entry->branchOffice,
            'guest_type' => $entry->guestType,
            'guest_document_id' => $entry->guest_document_id,
            'guest_full_name' => $entry->guest_full_name,
            'leader_full_name' => $entry->leader_full_name,
            'guest_signature' => $entry->guest_signature,
            'entry_in' => $entry->entry_in,
            'entry_out' => $entry->entry_out,
            'created_at' => $entry->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $entry->updated_at->format('Y-m-d H:i:s'),
        ];

        if ($user = JWTAuth::parseToken()->authenticate()) {
            if ($user->hasRole('admin')) {
                $jsonResponse['deleted_at'] = $entry->deleted_at;
                $jsonResponse['logs'] = $entry->logs->load('performedBy');
            }
        }

        return $jsonResponse;
    }
}
