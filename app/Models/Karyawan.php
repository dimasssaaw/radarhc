<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $with = ['department', 'position'];

    protected $fillable = [
        'npk',
        'nama',
        'foto',
        'tanggal_lahir',
        'pendidikan',
        'kontak',
        'email',
        'department_id',
        'position_id',
        'grade',
        'masuk_kerja',
        'iq',
        'readiness',
        'disc',
        'hav',
    ];

    public function setKontakAttribute($value)
    {
        // 1. Bersihkan semua karakter selain angka
        $value = preg_replace('/[^0-9]/', '', (string) $value);

        // 2. Jika berawalan '62', ganti jadi '0'
        if (strpos($value, '62') === 0) {
            $value = '0'.substr($value, 2);
        }

        // 3. Jika berawalan '8', tambahkan '0'
        if (strpos($value, '8') === 0) {
            $value = '0'.$value;
        }

        $this->attributes['kontak'] = $value;
    }

    public function getKontakAttribute($value)
    {
        $value = trim((string) $value);
        if (strpos($value, '8') === 0) {
            return '0'.$value;
        }

        return $value;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function viaCharacters()
    {
        return $this->hasMany(EmployeeViaCharacter::class, 'id_karyawan');
    }

    public function strengths()
    {
        return $this->hasMany(EmployeeStrength::class, 'id_karyawan');
    }

    public function scarf()
    {
        return $this->hasOne(EmployeeScarf::class, 'id_karyawan');
    }

    public function assessmentReports()
    {
        return $this->hasMany(AssessmentReport::class, 'id_karyawan');
    }

    public function mentorings()
    {
        return $this->hasMany(Mentoring::class, 'id_karyawan');
    }

    public function idps()
    {
        return $this->hasMany(Idp::class, 'id_karyawan');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'id_karyawan');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'id_karyawan');
    }

    public function perjalananKarirs()
    {
        return $this->hasMany(PerjalananKarir::class, 'id_karyawan');
    }
}
