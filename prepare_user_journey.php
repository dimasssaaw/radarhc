<?php

$file = __DIR__.'/resources/views/user/journey/show.blade.php';
$content = file_get_contents($file);

// 1. Change back route
$content = preg_replace('/route\(\'employee-journey.index\'\)/', 'route(\'user-journey.index\')', $content);

// 2. Remove all "@if(auth()->check() && auth()->user()->role === 'admin')" and the button inside it for SCARF, Via Character, Strengths, Mentoring
$content = preg_replace('/@if\(auth\(\)->check\(\) && auth\(\)->user\(\)->role === \'admin\'\).*?@endif/s', '', $content);

// 3. Remove "Tambah" Karier button
$content = preg_replace('/<button type="button" onclick="document.getElementById\(\'modalTambahKarier\'\).*?<\/button>/s', '', $content);

// 4. Remove Edit/Hapus Karier buttons
$content = preg_replace('/<div class="flex items-center gap-2">\s*<button type="button" onclick="document.getElementById\(\'modalEditKarier_.*?<\/form>\s*<\/div>/s', '', $content);

// 5. Replace form in Kesimpulan & Rekomendasi with read-only divs
// From <form action="{{ route('employee-journey.recommendation', $karyawan->id) }}" to </form>
// We'll just replace the form tag with a div, replace the select with a disabled select or plain text, replace textarea with readonly, and remove the submit button.
$content = preg_replace('/<form action="\{\{ route\(\'employee-journey.recommendation\'.*?>/s', '<div class="flex flex-col flex-grow">', $content);
$content = preg_replace('/<\/form>/', '</div>', $content);
$content = str_replace('name="keputusan"', 'disabled', $content);
$content = str_replace('name="catatan_rekomendasi_atasan"', 'disabled readonly', $content);
$content = preg_replace('/<button type="submit" class="w-full bg-slate-800.*?<\/button>/s', '', $content);

// 6. Remove @push('modals') ... @endpush completely since we don't need add/edit modals in the user view
$content = preg_replace('/@push\(\'modals\'\).*?@endpush/s', '', $content);

// 7. Remove script sections that initialize Select2 or handle modals if they exist (modals are removed, but maybe inline scripts)
// I will keep the Chart.js radar script, so I'll be careful.

// 8. General Translations
$replacements = [
    'Detail Employee Journey' => 'Employee Journey Detail',
    'Kembali' => 'Back',
    'Perjalanan Karier' => 'Career Journey',
    'Rekam jejak posisi dan pencapaian karyawan' => 'Track record of employee positions and achievements',
    'Awal Bergabung' => 'Joined',
    'Tetap di Posisi' => 'Stayed in Position',
    'Posisi Saat Ini' => 'Current Position',
    'Proyeksi Target' => 'Target Projection',
    'Selesai' => 'Completed',
    'Belum ada data perjalanan karir.' => 'No career journey data available.',
    'Detail Karier Tahun' => 'Career Detail Year',
    'Informasi Jabatan & Penghargaan' => 'Position & Award Information',
    'Posisi / Jabatan' => 'Position',
    'Tidak ada catatan detail yang direkam pada tahun ini.' => 'No detailed notes recorded for this year.',
    'Pencapaian Spesial' => 'Special Achievements',
    'Penghargaan tercatat pada tahun ini.' => 'Award recorded for this year.',
    'Belum Ada Penghargaan' => 'No Awards Yet',
    'Tidak ada penghargaan tercatat pada tahun ini.' => 'No awards recorded for this year.',
    'Kesimpulan & Rekomendasi' => 'Conclusion & Recommendation',
    'Evaluasi kualitatif assessor' => 'Assessor\'s qualitative evaluation',
    'Keputusan Assessor' => 'Assessor Decision',
    '-- Pilih Keputusan --' => '-- Select Decision --',
    'Catatan Kualitatif' => 'Qualitative Notes',
    'Ringkasan skor performa karyawan' => 'Employee performance score summary',
    'Kecerdasan Umum' => 'General Intelligence',
    'Sistematika Kerja' => 'Work Systematic',
    'Integritas' => 'Integrity',
    'Kepemimpinan' => 'Leadership',
    'Progress & Pemetaan Kompetensi' => 'Progress & Competency Mapping',
    'Progress Pelatihan' => 'Training Progress',
    'Pemenuhan target pelatihan' => 'Fulfillment of training targets',
    'Belum ada data sertifikasi.' => 'No certification data available.',
    'Belum ada data' => 'No data available',
    '(kosong)' => '(empty)',
];
$content = str_replace(array_keys($replacements), array_values($replacements), $content);

file_put_contents($file, $content);
echo "Successfully prepared user journey view.\n";
