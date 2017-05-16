<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntryLog extends Model
{
   	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['entry_id', 'performed_by', 'performed_at', 'requested_data'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'entry_id'];

    public $timestamps = false;

    public function entry()
    {
        return $this->belongsTo('App\Entry', 'entry_id');
    }

    public function performedBy()
    {
        return $this->belongsTo('App\User', 'performed_by');
    }
}
