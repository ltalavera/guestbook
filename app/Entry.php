<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'guest_type_id', 'visitor_id', 'guest_document_id', 'guest_full_name', 
        'leader_full_name', 'guest_signature', 'entry_in', 'entry_out'
    ];

    /**
     * Get the visitor associated
     */
    public function visitor()
    {
        return $this->belongsTo('App\Visitor');
    }

    public function branchOffice()
    {
        return $this->belongsTo('App\BranchOffice', 'branch_office_id');
    }

    public function guestType()
    {
        return $this->belongsTo('App\GuestType', 'guest_type_id');
    }

    public function logs()
    {
        return $this->hasMany('App\EntryLog');
    }
}
