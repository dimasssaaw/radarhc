<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerjalananKarir extends Model
{
    protected $table = 'perjalanan_karir';

    protected $fillable = [
        'id_karyawan',
        'department_id',
        'tahun_karir',
        'nama_posisi',
        'tipe_milestone',
        'status_pencapaian',
        'catatan_detail',
        'operational_award',
        'catatan_rekomendasi_atasan',
        'keputusan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
