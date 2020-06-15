<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'user_id', 'comments'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
