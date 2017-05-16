<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestType extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];
}
