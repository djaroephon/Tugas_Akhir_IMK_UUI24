<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';

    protected $fillable = ['nama', 'kapasitas'];

    public function anak()
    {
        return $this->hasMany(Anak::class);
    }

    public function donasi()
    {
        return $this->hasMany(Donasi::class);
    }
}
