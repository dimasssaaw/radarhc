<?php

namespace App\Imports;

use App\Models\Karyawan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        $defaultDeptId = 1;
        if (auth()->check() && strtolower(auth()->user()->role) !== 'admin') {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($userKaryawan && $userKaryawan->department_id) {
                $defaultDeptId = $userKaryawan->department_id;
            }
        }

        foreach ($rows as $row) {
            if (! isset($row['npk'])) {
                continue;
            }

            $karyawan = Karyawan::where('npk', $row['npk'])->first();

            if ($karyawan) {
                // Update existing employee (skip grade, iq, dll since they are manual)
                $karyawan->update([
                    'nama' => $row['nama'] ?? $karyawan->nama,
                    'position_id' => isset($row['jabatan']) ? (DB::table('positions')->where('nama_jabatan', trim($row['jabatan']))->first()?->id ?? $karyawan->position_id) : $karyawan->position_id,
                    'department_id' => isset($row['departemen']) ? (DB::table('departments')->where('nama_departemen', trim($row['departemen']))->first()?->id ?? $karyawan->department_id) : $karyawan->department_id,
                    'tanggal_lahir' => isset($row['tanggal_lahir']) ? $this->parseDate($row['tanggal_lahir']) : $karyawan->tanggal_lahir,
                    'masuk_kerja' => isset($row['masuk_kerja']) ? $this->parseDate($row['masuk_kerja']) : $karyawan->masuk_kerja,
                    'pendidikan' => $row['pendidikan'] ?? $karyawan->pendidikan,
                    'kontak' => $row['kontak'] ?? $karyawan->kontak,
                    'email' => $row['email'] ?? $karyawan->email,
                    'grade' => $row['grade'] ?? $karyawan->grade,
                    'iq' => isset($row['iq']) ? (int) $row['iq'] : $karyawan->iq,
                    'readiness' => isset($row['readiness']) ? $this->parseReadiness($row['readiness']) : $karyawan->readiness,
                    'disc' => $row['disc'] ?? $karyawan->disc,
                    'hav' => $row['hav'] ?? $karyawan->hav,
                ]);
            } else {
                // Create new with defaults for manual fields
                Karyawan::create([
                    'npk' => $row['npk'],
                    'nama' => $row['nama'] ?? 'Tanpa Nama',
                    'position_id' => isset($row['jabatan']) ? (DB::table('positions')->where('nama_jabatan', trim($row['jabatan']))->first()?->id) : null,
                    'department_id' => isset($row['departemen']) ? (DB::table('departments')->where('nama_departemen', trim($row['departemen']))->first()?->id ?? $defaultDeptId) : $defaultDeptId,
                    'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null) ?? now()->format('Y-m-d'),
                    'masuk_kerja' => $this->parseDate($row['masuk_kerja'] ?? null) ?? now()->format('Y-m-d'),
                    'pendidikan' => $row['pendidikan'] ?? '-',
                    'kontak' => $row['kontak'] ?? '-',
                    'email' => $row['email'] ?? '-',
                    'grade' => $row['grade'] ?? '-',
                    'iq' => isset($row['iq']) ? (int) $row['iq'] : 0,
                    'readiness' => $this->parseReadiness($row['readiness'] ?? null),
                    'disc' => $row['disc'] ?? '-',
                    'hav' => $row['hav'] ?? 'potential',
                ]);
            }
        }
    }

    private function parseReadiness($value)
    {
        if (empty($value)) {
            return 'not_ready';
        }
        $val = trim($value);
        if (in_array(strtoupper($val), ['R1', 'R2', 'R3', 'R4', 'R5'])) {
            return strtoupper($val).' ready';
        }

        return $val;
    }

    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        return date('Y-m-d', strtotime($value));
    }
}
