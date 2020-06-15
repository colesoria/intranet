<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_inicio', 'fecha_fin', 'day', 'user_id', 'comments', 'hora_innicio', 'hora_fin', 'document'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
