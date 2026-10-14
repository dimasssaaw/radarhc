<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeViaCharacter extends Model
{
    use HasFactory;

    protected $table = 'via_character';

    protected $fillable = [
        'id_karyawan',
        'overuse_strength',
        'underuse_strength',
    ];

    public function employee()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
