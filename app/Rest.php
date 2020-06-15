<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'in', 'out', 'sign_id'
    ];
    public function sign()
    {
        return $this->belongsTo('App\Sign');
    }
}
