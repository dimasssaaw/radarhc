<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $table = 'sertifikasi';

    protected $fillable = [
        'id_karyawan',
        'nama_sertifikasi',
        'penerbit',
        'terbit',
        'kadaluarsa',
        'status_sertifikat',
        'lampiran_sertifikasi',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
