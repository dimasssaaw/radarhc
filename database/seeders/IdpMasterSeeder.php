<?php

namespace Database\Seeders;

use App\Models\IdpCompetency;
use App\Models\IdpCompetencyGroup;
use Illuminate\Database\Seeder;

class IdpMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Department Head / Leadership Group
        $headGroup = IdpCompetencyGroup::firstOrCreate(
            ['title' => 'Leadership Competencies (Department Head)'],
            [
                'target_position' => 'department head',
                'description' => 'Evaluasi kompetensi kepemimpinan dan manajerial untuk level Department Head dan Section Head.',
                'order' => 1,
            ]
        );

        $headCompetencies = [
            'Managing Change',
            'Empowerment',
            'Fostering Teamwork',
            'Analysis & Decision Making',
            'Planning & Controlling',
            'Integrity & Ethics',
            'Excellence',
            'Compassion',
            'Humility',
        ];

        foreach ($headCompetencies as $idx => $name) {
            IdpCompetency::firstOrCreate(
                [
                    'idp_competency_group_id' => $headGroup->id,
                    'name' => $name,
                ],
                [
                    'target_score' => 5.0,
                    'order' => $idx + 1,
                ]
            );
        }

        // 2. Staff Level Group
        $staffGroup = IdpCompetencyGroup::firstOrCreate(
            ['title' => 'Staff Functional Competencies'],
            [
                'target_position' => 'staff',
                'description' => 'Evaluasi kompetensi fungsional, eksekusi kerja, dan kerjasama untuk level Staff.',
                'order' => 2,
            ]
        );

        $staffCompetencies = [
            'Leads Courageously',
            'Coach & Develop Others',
            'Teamwork',
            'Trouble Shooting',
            'Customer Satisfaction',
            'Cross Functional Capability',
            'Quality Focus',
            'Continuous Improvement',
            'Planning Monitoring',
            'Personal Integrity',
            'Drive For Result',
            'Learning',
        ];

        foreach ($staffCompetencies as $idx => $name) {
            IdpCompetency::firstOrCreate(
                [
                    'idp_competency_group_id' => $staffGroup->id,
                    'name' => $name,
                ],
                [
                    'target_score' => 5.0,
                    'order' => $idx + 1,
                ]
            );
        }

        // 3. Unit Head Group
        $unitGroup = IdpCompetencyGroup::firstOrCreate(
            ['title' => 'Unit Head Competencies'],
            [
                'target_position' => 'unit head',
                'description' => 'Evaluasi pengawasan unit kerja, monitoring, dan kualitas untuk level Unit Head.',
                'order' => 3,
            ]
        );

        $unitCompetencies = [
            'Directiveness',
            'Monitoring',
            'Teamwork',
            'Quality & Customer Focus',
            'Continuous Improvement',
            'Personal Integrity',
        ];

        foreach ($unitCompetencies as $idx => $name) {
            IdpCompetency::firstOrCreate(
                [
                    'idp_competency_group_id' => $unitGroup->id,
                    'name' => $name,
                ],
                [
                    'target_score' => 5.0,
                    'order' => $idx + 1,
                ]
            );
        }

        // 4. Core Values & Character (Berlaku untuk semua posisi)
        $coreGroup = IdpCompetencyGroup::firstOrCreate(
            ['title' => 'Core Values & Character'],
            [
                'target_position' => 'all',
                'description' => 'Evaluasi nilai-nilai inti perusahaan dan karakter dasar kerja untuk seluruh level karyawan.',
                'order' => 4,
            ]
        );

        $coreCompetencies = [
            'Integrity & Ethics',
            'Excellence & Commitment',
            'Compassion & Respect',
            'Humility & Openness',
            'Agility & Adaptability',
        ];

        foreach ($coreCompetencies as $idx => $name) {
            IdpCompetency::firstOrCreate(
                [
                    'idp_competency_group_id' => $coreGroup->id,
                    'name' => $name,
                ],
                [
                    'target_score' => 5.0,
                    'order' => $idx + 1,
                ]
            );
        }
    }
}
