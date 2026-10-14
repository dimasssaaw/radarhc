<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStrength extends Model
{
    use HasFactory;

    protected $table = 'strength_weakness';

    protected $fillable = [
        'id_karyawan',
        'strength',
        'weakness',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
