<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeScarf extends Model
{
    protected $table = 'scarf_profile';

    protected $fillable = [
        'id_karyawan',
        'status',
        'certainty',
        'autonomy',
        'relatedness',
        'fairness',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
