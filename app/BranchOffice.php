<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    public function entries()
    {
        return $this->hasMany('App\Entry');
    }

    /**
     * The visitors that belong to the branch office.
     */
    public function visitors()
    {
        return $this->belongsToMany('App\Visitor', 'entries', 'branch_office_id', 'visitor_id')->distinct();
    }

    public function users() 
    {
        return $this->hasMany('App\User');
    }
}
