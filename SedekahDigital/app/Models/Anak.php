<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    protected $table = 'anak';

    protected $fillable = [
        'nama',
        'usia',
        'latar_belakang',
        'foto',
        'kamar_id'
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
