<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_id', 'full_name', 'leader_full_name'
    ];

     /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * Get the entries for this visitor
     */
    public function entries()
    {
        return $this->hasMany('App\Entry');
    }
}
