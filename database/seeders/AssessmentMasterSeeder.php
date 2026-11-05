<?php

namespace Database\Seeders;

use App\Models\AssessmentAspect;
use App\Models\AssessmentCategory;
use App\Models\AssessmentProvider;
use Illuminate\Database\Seeder;

class AssessmentMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kategori: Cognitive Ability (Kemampuan Berpikir)
        $cognitive = AssessmentCategory::firstOrCreate(
            ['name' => 'Cognitive Ability (Kemampuan Berpikir)'],
            [
                'code' => 'KB',
                'description' => 'Evaluasi aspek kecerdasan intelektual, analisis, dan kemampuan konseptual berpikir.',
                'order' => 1,
            ]
        );

        $cognitiveAspects = [
            ['name' => 'General Intelligence', 'code' => 'kb_kecerdasan_umum', 'target_score' => 5.0],
            ['name' => 'Thinking Flexibility', 'code' => 'kb_fleksibilitas_berfikir', 'target_score' => 5.0],
            ['name' => 'Conceptual Ability', 'code' => 'kb_kemampuan_konseptual', 'target_score' => 5.0],
            ['name' => 'Analysis & Synthesis', 'code' => 'kb_analisa_sintesa', 'target_score' => 5.0],
        ];

        foreach ($cognitiveAspects as $idx => $item) {
            AssessmentAspect::firstOrCreate(
                ['category_id' => $cognitive->id, 'name' => $item['name']],
                ['code' => $item['code'], 'target_score' => $item['target_score'], 'order' => $idx + 1]
            );
        }

        // 2. Kategori: Work Attitude (Sikap Kerja)
        $workAttitude = AssessmentCategory::firstOrCreate(
            ['name' => 'Work Attitude (Sikap Kerja)'],
            [
                'code' => 'SK',
                'description' => 'Evaluasi sistematika, ketahanan, fleksibilitas, dan ketelitian dalam bekerja.',
                'order' => 2,
            ]
        );

        $workAttitudeAspects = [
            ['name' => 'Systematic Work', 'code' => 'sk_sistematika_kerja', 'target_score' => 5.0],
            ['name' => 'Endurance', 'code' => 'sk_daya_tahan', 'target_score' => 5.0],
            ['name' => 'Flexibility', 'code' => 'sk_fleksibilitas', 'target_score' => 5.0],
            ['name' => 'Attention to Detail', 'code' => 'sk_perhatian_detail', 'target_score' => 5.0],
        ];

        foreach ($workAttitudeAspects as $idx => $item) {
            AssessmentAspect::firstOrCreate(
                ['category_id' => $workAttitude->id, 'name' => $item['name']],
                ['code' => $item['code'], 'target_score' => $item['target_score'], 'order' => $idx + 1]
            );
        }

        // 3. Kategori: Personality Characteristics (Karakteristik Kepribadian)
        $personality = AssessmentCategory::firstOrCreate(
            ['name' => 'Personality Characteristics (Karakteristik Kepribadian)'],
            [
                'code' => 'KP',
                'description' => 'Evaluasi stabilitas emosi, penyesuaian diri, kepemimpinan, dan motivasi kerja.',
                'order' => 3,
            ]
        );

        $personalityAspects = [
            ['name' => 'Emotional Stability', 'code' => 'kp_stabilitas_emosi', 'target_score' => 5.0],
            ['name' => 'Self Adjustment', 'code' => 'kp_penyesuaian_diri', 'target_score' => 5.0],
            ['name' => 'Self Confidence', 'code' => 'kp_kepercayaan_diri', 'target_score' => 5.0],
            ['name' => 'Leadership', 'code' => 'kp_kepemimpinan', 'target_score' => 5.0],
            ['name' => 'Integrity', 'code' => 'kp_integritas', 'target_score' => 5.0],
            ['name' => 'Work Motivation', 'code' => 'kp_motivasi_kerja', 'target_score' => 5.0],
        ];

        foreach ($personalityAspects as $idx => $item) {
            AssessmentAspect::firstOrCreate(
                ['category_id' => $personality->id, 'name' => $item['name']],
                ['code' => $item['code'], 'target_score' => $item['target_score'], 'order' => $idx + 1]
            );
        }

        // 4. Kategori: Managerial Skills (Keterampilan Manajerial)
        $managerial = AssessmentCategory::firstOrCreate(
            ['name' => 'Managerial Skills (Keterampilan Manajerial)'],
            [
                'code' => 'MS',
                'description' => 'Evaluasi kompetensi manajerial, perencanaan, pengendalian, dan pengambilan keputusan.',
                'order' => 4,
            ]
        );

        $managerialAspects = [
            ['name' => 'Planning & Controlling', 'code' => 'ms_planning_controlling', 'target_score' => 5.0],
            ['name' => 'Managing Changes', 'code' => 'ms_managing_changes', 'target_score' => 5.0],
            ['name' => 'Empowerment', 'code' => 'ms_empowerment', 'target_score' => 5.0],
            ['name' => 'Fostering Teamwork', 'code' => 'ms_fostering_team_work', 'target_score' => 5.0],
            ['name' => 'Analysis & Decision', 'code' => 'ms_analysis_decision', 'target_score' => 5.0],
            ['name' => 'Humility', 'code' => 'ms_humility', 'target_score' => 5.0],
            ['name' => 'Compassion', 'code' => 'ms_compassion', 'target_score' => 5.0],
            ['name' => 'Excellence', 'code' => 'ms_excellence', 'target_score' => 5.0],
            ['name' => 'Integrity & Ethics', 'code' => 'ms_integrity_ethics', 'target_score' => 5.0],
        ];

        foreach ($managerialAspects as $idx => $item) {
            AssessmentAspect::firstOrCreate(
                ['category_id' => $managerial->id, 'name' => $item['name']],
                ['code' => $item['code'], 'target_score' => $item['target_score'], 'order' => $idx + 1]
            );
        }

        // 5. Providers Assessment Awal
        $providers = [
            ['name' => 'Daya Dimensi Indonesia (DDI)', 'description' => 'Assessment Center & Leadership Development Vendor', 'contact' => 'info@dayadimensi.co.id'],
            ['name' => 'PPM Manajemen', 'description' => 'Lembaga Manajemen & Asesmen Kompetensi Eksekutif', 'contact' => 'layanan@ppm-manajemen.ac.id'],
            ['name' => 'Quantum HRM', 'description' => 'Psikotes & Asesmen Potensi SDM', 'contact' => 'contact@quantumhrm.com'],
            ['name' => 'Assessment Center Internal', 'description' => 'Tim Asesor Internal Human Capital Radar HC', 'contact' => 'hc@radarhc.internal'],
        ];

        foreach ($providers as $idx => $p) {
            AssessmentProvider::firstOrCreate(
                ['name' => $p['name']],
                ['description' => $p['description'], 'contact' => $p['contact'], 'order' => $idx + 1]
            );
        }
    }
}
