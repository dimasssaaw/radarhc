<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idp extends Model
{
    use HasFactory;

    protected $table = 'idp';

    protected $guarded = ['id'];

    protected $casts = [
        'selected_charts' => 'array',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function penilaian()
    {
        return $this->hasOne(IdpPenilain::class, 'id_idp');
    }

    public function competencyScores()
    {
        return $this->hasMany(IdpCompetencyScore::class, 'id_idp');
    }
}
