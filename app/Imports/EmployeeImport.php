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
    /**
     * @var array<string, int>
     */
    private array $resolvedDeptCache = [];

    /**
     * @var array<string, int>
     */
    private array $resolvedPosCache = [];

    /**
     * @var array<int, string>
     */
    public array $warnings = [];

    public function collection(Collection $rows): void
    {
        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';

        $defaultDeptId = 1;
        if (! $isAdmin && auth()->check()) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($userKaryawan && $userKaryawan->department_id) {
                $defaultDeptId = $userKaryawan->department_id;
            }
        }

        DB::transaction(function () use ($rows, $defaultDeptId, $isAdmin) {
            // Clean any existing '-' emails in database that might violate uniqueness
            DB::table('karyawan')->where('email', '-')->update(['email' => null]);

            // Pre-process rows: collect all valid incoming emails from the import file
            $incomingEmails = [];
            foreach ($rows as $row) {
                $rawEmail = $this->getEmailRowValue($row);
                $cleaned = $this->cleanAndValidateEmail($rawEmail);
                if ($cleaned !== null) {
                    $incomingEmails[] = $cleaned;
                }
            }

            // Temporarily set to null any matching emails in the database so swapping emails across employees
            // (e.g. employee A gets employee B's email) does not trigger duplicate unique constraints
            if (! empty($incomingEmails)) {
                DB::table('karyawan')->whereIn('email', array_unique($incomingEmails))->update(['email' => null]);
            }

            $seenEmails = []; // format: [email => npk]

            foreach ($rows as $row) {
                $rawNpk = $this->getRowValue($row, ['npk', 'no_induk', 'nomor_induk', 'nip']);
                if ($rawNpk === null || trim((string) $rawNpk) === '') {
                    continue;
                }
                $npk = trim((string) $rawNpk);

                $rawEmail = $this->getEmailRowValue($row);
                $isDash = $rawEmail !== null && trim((string) $rawEmail) === '-';
                $validEmail = $this->cleanAndValidateEmail($rawEmail);

                $email = null;
                $shouldClearEmail = $isDash;

                if ($validEmail !== null) {
                    if (! isset($seenEmails[$validEmail])) {
                        $email = $validEmail;
                        $seenEmails[$validEmail] = $npk;
                    } else {
                        $this->warnings[] = "Email '{$validEmail}' pada NPK {$npk} diabaikan karena duplikat dengan NPK {$seenEmails[$validEmail]}.";
                    }
                } elseif ($rawEmail !== null && ! $isDash && trim((string) $rawEmail) !== '') {
                    $cleanRaw = trim((string) $rawEmail);
                    $this->warnings[] = "Format email '{$cleanRaw}' pada NPK {$npk} tidak valid sehingga dikosongkan.";
                }

                $karyawan = Karyawan::where('npk', $npk)->first();
                if (! $karyawan && is_numeric($npk)) {
                    $karyawan = Karyawan::whereRaw('CAST(npk AS UNSIGNED) = ?', [(int) $npk])->first();
                }

                $rawDept = $this->getRowValue($row, ['departemen', 'departement', 'department', 'dept', 'nama_departemen', 'divisi']);
                $rawJabatan = $this->getRowValue($row, ['jabatan', 'position', 'posisi', 'nama_jabatan', 'role_jabatan']);
                $nama = $this->getRowValue($row, ['nama', 'nama_lengkap', 'name', 'employee_name']);
                $tglLahir = $this->getRowValue($row, ['tanggal_lahir', 'tgl_lahir', 'birth_date', 'tanggal_lahir_yyyy_mm_dd']);
                $masukKerja = $this->getRowValue($row, ['masuk_kerja', 'tgl_masuk', 'tanggal_masuk', 'lama_bekerja', 'join_date']);
                $pendidikan = $this->getRowValue($row, ['pendidikan', 'education', 'tingkat_pendidikan']);
                $kontak = $this->getRowValue($row, ['kontak', 'no_hp', 'no_telepon', 'telepon', 'phone', 'contact']);
                $grade = $this->getRowValue($row, ['grade', 'golongan']);
                $iq = $this->getRowValue($row, ['iq']);
                $readiness = $this->getRowValue($row, ['readiness', 'kesiapan']);
                $disc = $this->getRowValue($row, ['disc']);
                $hav = $this->getRowValue($row, ['hav']);

                $deptId = $this->resolveDepartmentId($rawDept, $karyawan?->department_id ?? $defaultDeptId, $isAdmin);
                $posId = $this->resolvePositionId($rawJabatan, $karyawan?->position_id, $isAdmin);

                if ($karyawan) {
                    $updateData = [
                        'nama' => $nama ?? $karyawan->nama,
                        'position_id' => $posId,
                        'department_id' => $deptId,
                        'tanggal_lahir' => $tglLahir !== null ? $this->parseDate($tglLahir) : $karyawan->tanggal_lahir,
                        'masuk_kerja' => $masukKerja !== null ? $this->parseDate($masukKerja) : $karyawan->masuk_kerja,
                        'pendidikan' => $pendidikan ?? $karyawan->pendidikan,
                        'kontak' => $kontak ?? $karyawan->kontak,
                        'grade' => $grade ?? $karyawan->grade,
                        'iq' => $iq !== null && trim((string) $iq) !== '' ? (int) $iq : $karyawan->iq,
                        'readiness' => $readiness !== null ? $this->parseReadiness($readiness) : $karyawan->readiness,
                        'disc' => $disc ?? $karyawan->disc,
                        'hav' => $hav !== null ? $this->parseHav($hav) : $karyawan->hav,
                    ];

                    if ($email !== null) {
                        $updateData['email'] = $email;
                    } elseif ($shouldClearEmail) {
                        $updateData['email'] = null;
                    }

                    $karyawan->update($updateData);
                } else {
                    // Create new with defaults for manual fields
                    Karyawan::create([
                        'npk' => $npk,
                        'nama' => $nama ?? 'Tanpa Nama',
                        'position_id' => $posId,
                        'department_id' => $deptId,
                        'tanggal_lahir' => $this->parseDate($tglLahir) ?? null,
                        'masuk_kerja' => $this->parseDate($masukKerja) ?? null,
                        'pendidikan' => $pendidikan ?? '-',
                        'kontak' => $kontak ?? '-',
                        'email' => $email,
                        'grade' => $grade ?? '-',
                        'iq' => $iq !== null && trim((string) $iq) !== '' ? (int) $iq : null,
                        'readiness' => $this->parseReadiness($readiness),
                        'disc' => $disc ?? '-',
                        'hav' => $this->parseHav($hav),
                    ]);
                }
            }
        });
    }

    /**
     * Retrieve email column value supporting exact and fuzzy column names.
     *
     * @param  Collection|array<string, mixed>  $row
     */
    private function getEmailRowValue(Collection|array $row): mixed
    {
        $keys = [
            'email', 'alamat_email', 'e_mail', 'mail', 'alamat_e_mail', 'email_karyawan',
            'email_address', 'surel', 'alamat_surel', 'email_kantor', 'email_perusahaan',
            'work_email', 'electronic_mail', 'kontak_email',
        ];

        $val = $this->getRowValue($row, $keys);
        if ($val !== null) {
            return $val;
        }

        // Fuzzy match: check if any header contains 'email' or 'surel'
        $allKeys = $row instanceof Collection ? $row->keys() : array_keys($row);
        foreach ($allKeys as $k) {
            $cleanK = strtolower(trim((string) $k));
            if (str_contains($cleanK, 'email') || str_contains($cleanK, 'surel') || $cleanK === 'mail') {
                $possibleVal = $row instanceof Collection ? $row->get($k) : ($row[$k] ?? null);
                if ($possibleVal !== null && trim((string) $possibleVal) !== '') {
                    return $possibleVal;
                }
            }
        }

        return null;
    }

    /**
     * Clean and validate incoming email string.
     */
    private function cleanAndValidateEmail(mixed $rawVal): ?string
    {
        if ($rawVal === null) {
            return null;
        }

        $val = (string) $rawVal;

        // 1. Remove non-breaking spaces (\u00A0, \xC2\xA0, chr(160)), zero-width spaces, and control characters
        $val = str_replace(["\xc2\xa0", "\xa0", "\u{00a0}", "\u{200b}", "\u{200c}", "\u{200d}", "\u{feff}"], ' ', $val);

        // 2. Trim regular spaces and control characters
        $val = trim($val, " \t\n\r\0\x0B");

        if ($val === '' || $val === '-') {
            return null;
        }

        // 3. Remove "mailto:" prefix if present
        if (stripos($val, 'mailto:') === 0) {
            $val = trim(substr($val, 7));
        }

        // 4. If multiple emails are provided (separated by ;, /, ,, |, or spaces), take the first valid one
        if (preg_match('/[;,\/\|\s]/', $val)) {
            $parts = preg_split('/[;,\/\|\s]+/', $val);
            if ($parts) {
                foreach ($parts as $part) {
                    $cleanedPart = trim($part, " \t\n\r\0\x0B.,;:'\"`");
                    if ($cleanedPart !== '' && filter_var($cleanedPart, FILTER_VALIDATE_EMAIL)) {
                        $val = $cleanedPart;
                        break;
                    }
                }
            }
        }

        // 5. Trim any trailing dots, commas, or quotes (e.g. budi@company.com.)
        $val = rtrim($val, " .,;:'\"`");

        // 6. Fix common typos like "@gmail,com" -> "@gmail.com"
        if (preg_match('/@([a-zA-Z0-9.-]+),([a-zA-Z]{2,})$/', $val)) {
            $val = (string) preg_replace('/@([a-zA-Z0-9.-]+),([a-zA-Z]{2,})$/', '@$1.$2', $val);
        }

        // 7. Validate with filter_var
        $val = strtolower($val);
        if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
            return $val;
        }

        return null;
    }

    /**
     * @param  Collection|array<string, mixed>  $row
     * @param  array<int, string>  $keys
     */
    private function getRowValue(Collection|array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            $val = $row instanceof Collection ? $row->get($key) : ($row[$key] ?? null);
            if ($val !== null && trim((string) $val) !== '') {
                return $val;
            }
        }

        return null;
    }

    private function resolveDepartmentId(mixed $rawVal, int $defaultDeptId, bool $isAdmin): int
    {
        if ($rawVal === null) {
            return $defaultDeptId;
        }

        $val = trim((string) $rawVal);
        if ($val === '' || $val === '-') {
            return $defaultDeptId;
        }

        $cacheKey = strtolower($val);
        if (isset($this->resolvedDeptCache[$cacheKey])) {
            return $this->resolvedDeptCache[$cacheKey];
        }

        // 1. If numeric, check if ID exists
        if (is_numeric($val)) {
            $deptById = DB::table('departments')->where('id', (int) $val)->first();
            if ($deptById) {
                $this->resolvedDeptCache[$cacheKey] = $deptById->id;

                return $deptById->id;
            }
        }

        // 2. Exact match (case-insensitive)
        $dept = DB::table('departments')
            ->whereRaw('LOWER(TRIM(nama_departemen)) = ?', [$cacheKey])
            ->first();
        if ($dept) {
            $this->resolvedDeptCache[$cacheKey] = $dept->id;

            return $dept->id;
        }

        // 3. Known aliases
        $aliases = [
            'ppic' => 'Production & PPIC',
            'production' => 'Production & PPIC',
            'prod & ppic' => 'Production & PPIC',
            'qc' => 'Quality Control',
            'qa' => 'Quality Assurance',
            'cr' => 'Customer Relation',
            'cs' => 'Customer Relation',
            'customer relations' => 'Customer Relation',
            'purchasing' => 'Purchasing',
            'purchase' => 'Purchasing',
            'maint' => 'Maintenance',
            'eng' => 'Engineering',
        ];

        if (isset($aliases[$cacheKey])) {
            $deptAlias = DB::table('departments')
                ->whereRaw('LOWER(TRIM(nama_departemen)) = ?', [strtolower($aliases[$cacheKey])])
                ->first();
            if ($deptAlias) {
                $this->resolvedDeptCache[$cacheKey] = $deptAlias->id;

                return $deptAlias->id;
            }
        }

        // 4. Substring / LIKE match
        $partialDept = DB::table('departments')
            ->where('nama_departemen', 'LIKE', '%'.$val.'%')
            ->first();
        if ($partialDept) {
            $this->resolvedDeptCache[$cacheKey] = $partialDept->id;

            return $partialDept->id;
        }

        // 5. If Admin is importing and department does not exist, create it automatically
        if ($isAdmin) {
            $newId = DB::table('departments')->insertGetId([
                'nama_departemen' => $val,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->resolvedDeptCache[$cacheKey] = $newId;

            return $newId;
        }

        return $defaultDeptId;
    }

    private function resolvePositionId(mixed $rawVal, ?int $currentPositionId, bool $isAdmin): ?int
    {
        if ($rawVal === null) {
            return $currentPositionId;
        }

        $val = trim((string) $rawVal);
        if ($val === '' || $val === '-') {
            return $currentPositionId;
        }

        $cacheKey = strtolower($val);
        if (isset($this->resolvedPosCache[$cacheKey])) {
            return $this->resolvedPosCache[$cacheKey];
        }

        // 1. If numeric, check if ID exists
        if (is_numeric($val)) {
            $posById = DB::table('positions')->where('id', (int) $val)->first();
            if ($posById) {
                $this->resolvedPosCache[$cacheKey] = $posById->id;

                return $posById->id;
            }
        }

        // 2. Exact match (case-insensitive)
        $pos = DB::table('positions')
            ->whereRaw('LOWER(TRIM(nama_jabatan)) = ?', [$cacheKey])
            ->first();
        if ($pos) {
            $this->resolvedPosCache[$cacheKey] = $pos->id;

            return $pos->id;
        }

        // 3. Known aliases
        $aliases = [
            'head' => 'Department Head',
            'dept head' => 'Department Head',
            'dept. head' => 'Department Head',
            'dh' => 'Department Head',
            'department head' => 'Department Head',
            'staff' => 'Staff',
            'staf' => 'Staff',
            'unit head' => 'Unit Head',
            'uh' => 'Unit Head',
            'group head' => 'Group Head',
            'gh' => 'Group Head',
            'section head' => 'Section Head',
            'sh' => 'Section Head',
        ];

        if (isset($aliases[$cacheKey])) {
            $posAlias = DB::table('positions')
                ->whereRaw('LOWER(TRIM(nama_jabatan)) = ?', [strtolower($aliases[$cacheKey])])
                ->first();
            if ($posAlias) {
                $this->resolvedPosCache[$cacheKey] = $posAlias->id;

                return $posAlias->id;
            }
        }

        // 4. Substring / LIKE match
        $partialPos = DB::table('positions')
            ->where('nama_jabatan', 'LIKE', '%'.$val.'%')
            ->first();
        if ($partialPos) {
            $this->resolvedPosCache[$cacheKey] = $partialPos->id;

            return $partialPos->id;
        }

        // 5. If Admin and position doesn't exist, create it automatically
        if ($isAdmin) {
            $newPosId = DB::table('positions')->insertGetId([
                'nama_jabatan' => ucwords(strtolower($val)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->resolvedPosCache[$cacheKey] = $newPosId;

            return $newPosId;
        }

        return $currentPositionId;
    }

    private function parseReadiness(mixed $value): string
    {
        if (empty($value) || trim((string) $value) === '-') {
            return 'not_ready';
        }
        $val = trim((string) $value);
        if (in_array(strtoupper($val), ['R1', 'R2', 'R3', 'R4', 'R5'], true)) {
            return strtoupper($val).' ready';
        }

        $valid = ['R1 ready', 'R2 ready', 'R3 ready', 'R4 ready', 'R5 ready', 'not_ready'];

        return in_array($val, $valid, true) ? $val : 'not_ready';
    }

    private function parseHav(mixed $value): string
    {
        $valid = ['star', 'high potential', 'potential', 'excellent career person', 'career person', 'problem employee', 'deadwood'];
        if (empty($value) || trim((string) $value) === '-') {
            return 'potential';
        }

        $val = strtolower(trim((string) $value));

        return in_array($val, $valid, true) ? $val : 'potential';
    }

    private function parseDate(mixed $value): ?string
    {
        if (empty($value) || trim((string) $value) === '-') {
            return null;
        }
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }

        $timestamp = strtotime((string) $value);

        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }
}
