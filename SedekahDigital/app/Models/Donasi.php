<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
     protected $table = 'donasi';

    protected $fillable = [
        'nama_donatur',
        'asal',
        'jenis',
        'nominal',
        'nama_barang',
        'keterangan',
        'bukti',
        'status',
        'kamar_id'
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
