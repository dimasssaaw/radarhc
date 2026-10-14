<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeTemplateExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return [
            'npk',
            'nama',
            'jabatan',
            'departemen',
            'tanggal_lahir',
            'masuk_kerja',
            'pendidikan',
            'kontak',
            'email',
            'grade',
            'iq',
            'readiness',
            'disc',
            'hav',
        ];
    }

    public function array(): array
    {
        return [
            [
                '12345',
                'Budi Santoso',
                'staff',
                'IT',
                '1990-01-01',
                '2020-05-10',
                'S1 Teknik Informatika',
                '08123456789',
                'budi.santoso@example.com',
                '4A',
                '120',
                'R1 ready',
                'D',
                'star',
            ],
            [
                '12346',
                'Siti Aminah',
                'head',
                'HR',
                '1985-12-15',
                '2015-08-20',
                'S2 Manajemen',
                '08987654321',
                'siti.aminah@example.com',
                '5B',
                '115',
                'not_ready',
                'I',
                'potential',
            ],
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
