<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_inicio', 'fecha_fin', 'total_days', 'user_id', 'comments'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
