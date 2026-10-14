# Product Requirement Document (PRD)
# RADAR HC – Digital Employee Journey Platform

---

## 1. Informasi Dokumen & Metadata

| Atribut | Keterangan |
| :--- | :--- |
| **Nama Produk** | **Radar HC (Radar Human Capital)** |
| **Tagline** | *Digital Employee Journey: Empowering Enterprise Decisions through Intelligent Workforce Insights* |
| **Versi Dokumen** | 1.0.0 |
| **Status Dokumen** | Approved / Baseline |
| **Tanggal Terbit** | 8 September 2026 |
| **Target Rilis** | Q3 - Q4 2026 |
| **Tech Stack Utama** | Laravel 13, PHP 8.4, Tailwind CSS v4, SQLite / MySQL, Blade / Vite |

---

## 2. Ringkasan Eksekutif (Executive Summary)

**Radar HC** adalah platform enterprise Human Capital berbasis web yang dirancang untuk mendigitalkan dan mengonsolidasikan seluruh siklus perjalanan karyawan (*Employee Journey*), mulai dari profil psikometrik, hasil asesmen kompetensi, rencana pengembangan individu (*Individual Development Plan* / IDP), sesi *mentoring/coaching*, riwayat pelatihan & sertifikasi, hingga pemetaan suksesi dan keputusan karir (*Career Milestone & Succession Decisions*).

Aplikasi ini memberikan visibilitas komprehensif bagi manajemen puncak (*Management*), pimpinan unit kerja (*Department Head* dan *Unit Head*), serta divisi *Human Capital* dalam menganalisis kesiapan talenta (*readiness*), matriks potensi karyawan (*Human Asset Value* / HAV 9-Box), serta menyelaraskan pertumbuhan talenta individu dengan tujuan strategis perusahaan.

---

## 3. Latar Belakang & Masalah Bisnis (Problem Statement)

### 3.1. Masalah yang Dihadapi (Pain Points)
1. **Silo Data Talenta**: Hasil asesmen psikotes, evaluasi kinerja, laporan IDP, dan sertifikasi karyawan tersebar di berbagai dokumen terpisah (Excel, PDF, arsip manual), mempersulit pembacaan profil menyeluruh.
2. **Ketiadaan Visibilitas Terintegrasi untuk Department Head**: Atasan langsung kerap kesulitan memantau kelemahan, kekuatan, serta rencana pengembangan anggota timnya secara real-time.
3. **Pemantauan Mentoring & IDP yang Tidak Terstruktur**: Sesi mentoring dan tindak lanjut rencana aksi seringkali kehilangan jejak implementasi tanpa integrasi model coaching terstandar (seperti GROW Model).
4. **Subjektivitas Keputusan Karir & Suksesi**: Perencanaan suksesi dan promosi belum didukung oleh visualisasi radar kompetensi dan histori perkembangan yang transparan dan berbasis data (*data-driven decision making*).

### 3.2. Solusi yang Dihadirkan oleh Radar HC
- **Pusat Profiling Karyawan 360°**: Menggabungkan demografi, IQ, DISC, SCARF Profile, dan VIA Character Strengths ke dalam satu dashboard terpadu.
- **Radar Kompetensi Multi-Dimensi**: Visualisasi komparatif kemampuan berpikir, sikap kerja, kepribadian, dan keahlian manajerial.
- **Modul Mentoring Berbasis Framework GROW**: Memastikan setiap sesi mentoring memiliki sasaran (*Goals*), evaluasi realitas (*Reality*), opsi strategi (*Options*), komitmen eksekusi (*Wrap-up*), serta pembelajaran (*Insight*).
- **IDP & Rubrik Evaluasi Bertingkat**: Rubrik kompetensi yang disesuaikan secara dinamis untuk level Head, Unit Head, dan Staff.
- **Jejak Karir & Pengambilan Keputusan Formal**: Pencatatan terverifikasi untuk suksesi, rotasi, promosi, program perbaikan kinerja (PIP), atau pengakhiran kontrak.

---

## 4. Tujuan Produk (Product Goals & Objectives)

| No | Sasaran Utama | Ukuran Keberhasilan (Target Metric) |
| :--- | :--- | :--- |
| 1 | Sentralisasi Riwayat Pengembangan Karyawan | 100% data karyawan, hasil asesmen, dan sertifikasi tercatat digital |
| 2 | Peningkatan Kedisiplinan Mentoring & IDP | > 90% karyawan memiliki IDP aktif yang termonitor berkala |
| 3 | Akselerasi Keputusan Promosi & Rotasi | Waktu penentuan kandidat suksesi berkurang hingga 60% |
| 4 | Transparansi Kesenjangan Kompetensi (*Skill Gap*) | Identifikasi kebutuhan pelatihan yang presisi berbasis data riil |

---

## 5. Pengguna & Peran Sistem (User Personas & RBAC)

Sistem Radar HC menerapkan kontrol akses berbasis peran (*Role-Based Access Control* / RBAC) yang terikat dengan Departemen:

| Peran (*Role*) | Deskripsi & Tanggung Jawab | Hak Akses Utama |
| :--- | :--- | :--- |
| **Admin / HC Admin** | Pengelola sistem Human Capital pusat | - Manajemen master data departemen & pengguna<br>- Input & upload laporan asesmen eksternal<br>- Monitoring global seluruh departemen<br>- Konfigurasi target training tahunan |
| **Head (Department Head / Group Head)** | Pimpinan departemen / divisi | - Mengakses dashboard divisi spesifik<br>- Melihat profil komprehensif seluruh staf di bawahnya<br>- Mengisi evaluasi IDP level Head & Staff<br>- Memberikan rekomendasi suksesi dan keputusan karir |
| **Unit Head** | Supervisor / Pimpinan unit kerja | - Memonitor staf dalam unit kerjanya<br>- Melakukan sesi mentoring menggunakan format GROW<br>- Mengisi rubrik penilaian IDP level Unit Head |
| **Staff / Karyawan** | Karyawan / individu dalam organisasi | - Melihat profil pribadi dan radar kompetensi diri<br>- Mengajukan dan memperbarui progres IDP<br>- Mengunggah sertifikat dan lampiran riwayat training<br>- Meninjau catatan feedback mentoring |

---

## 6. Arsitektur Basis Data & Entitas Utama

Berikut adalah entitas data yang telah didefinisikan dalam arsitektur basis data Radar HC:

```
[departemen1]
     |--- 1:N ---> [users]
     |--- 1:N ---> [karyawan]
                      |--- 1:1 ---> [strength_weakness]
                      |--- 1:1 ---> [via_character]
                      |--- 1:1 ---> [scarf_profile]
                      |--- 1:N ---> [assessment_report]
                      |--- 1:N ---> [mentoring]
                      |--- 1:N ---> [idp]
                                      |--- 1:N ---> [idp_penilain]
                      |--- 1:1 ---> [training]
                                      |--- 1:N ---> [riwayat_training]
                      |--- 1:N ---> [sertifikasi]
                      |--- 1:N ---> [perjalanan_karir]
```

### 6.1. Kamus Data & Struktur Tabel

#### A. Tabel Master & Autentikasi
1. **`departemen1`**: Menyimpan data unit departemen (`nama_departemen`).
2. **`users`**: Akun pengguna sistem dengan atribut: `name`, `email`, `password`, `role` (`head`, `staff`, `unit head`, `group head`, `admin`, `user`), `id_departemen`.
3. **`karyawan`**: Data induk kepegawaian:
   - Identitas: `npk`, `nama`, `foto`, `tanggal_lahir`, `pendidikan`, `kontak`.
   - Organisasi: `id_departemen`, `jabatan` (`head`, `staff`, `unit head`, `group head`), `grade`, `lama_bekerja`.
   - Indikator Talenta: `iq`, `readiness` (`R1 ready`, `R2 ready`, `R3 ready`, `R4 ready`, `R5 ready`, `not_ready`), `disc`, `hav` (*Human Asset Value*: `star`, `high potential`, `potential`, `excellent career person`, `career person`, `problem employee`, `deadwood`).

#### B. Profil Psikometri & Karakter
4. **`strength_weakness`**: Catatan naratif kekuatan (`strength`) dan area pengembangan (`weakness`).
5. **`via_character`**: Pengukuran kekuatan karakter berdasarkan *VIA Institute on Character*: `overuse_strength` (kekuatan berlebih yang berpotensi menjadi bumerang) dan `underuse_strength` (kekuatan terpendam yang belum teroptimasi).
6. **`scarf_profile`**: Pemetaan 5 domain motivasi sosial neuroleadership:
   - `status`: Keinginan terhadap pengakuan dan posisi relatif.
   - `certainty`: Kebutuhan akan kepastian dan kejelasan arah.
   - `autonomy`: Kebutuhan atas kendali mandiri dalam pekerjaan.
   - `relatedness`: Kebutuhan akan rasa aman berelasi dan kolaborasi.
   - `fairness`: Kebutuhan akan keadilan dan transparansi.

#### C. Asesmen & Radar Kompetensi
7. **`assessment_report`**: Laporan asesmen formal tahunan/berkala:
   - Meta: `tahun`, `periode`, `provider`.
   - **Kemampuan Berpikir (KB)**: `kb_kecerdasan_umum`, `kb_fleksibilitas_berfikir`, `kb_kemampuan_konseptual`, `kb_analisa_sintesa`.
   - **Sikap Kerja (SK)**: `sk_sistematika_kerja`, `sk_daya_tahan`, `sk_fleksibilitas`, `sk_perhatian_detail`.
   - **Kepribadian (KP)**: `kp_stabilitas_emosi`, `kp_penyesuaian_diri`, `kp_kepercayaan_diri`, `kp_kepemimpinan`, `kp_integritas`, `kp_motivasi_kerja`.
   - **Managerial Skills (MS)**: `ms_planning_controlling`, `ms_managing_changes`, `ms_empowerment`, `ms_fostering_team_work`, `ms_analysis_decision`, `ms_humility`, `ms_compassion`, `ms_excellence`, `ms_integrity_ethics`.

#### D. Mentoring & Coaching
8. **`mentoring`**: Manajemen sesi mentoring berbasis alur **GROW**:
   - Meta: `tahun`, `periode`, `mentor`, `development`, `target`, `monitoring`, `final`, `status_mentoring` (`pending`, `completed`, `cancelled`).
   - Konten GROW: `grow_goals`, `grow_realitas`, `grow_options`, `grow_wrap_up`, `grow_insight`.

#### E. Rencana Pengembangan Individu (IDP)
9. **`idp`**: Rencana pengembangan individu karyawan:
   - Meta: `tahun`, `periode`, `mentor`, `development`, `target`, `monitoring`, `final`, `status_idp` (`pending`, `completed`, `cancelled`).
   - Skor Kompetensi Pokok: `comp_planning`, `comp_adaptability`, `comp_leadership`, `comp_communication`, `comp_problem_solving`, `comp_technical_skill`.
10. **`idp_penilain`**: Lembar rubrik penilaian kompetensi spesifik per jenjang:
    - **Kompetensi Head**: `head_managing_change`, `head_empowerment`, `head_fostering_teamwork`, `head_analysis_decision`, `head_planning_controlling`, `head_integrity_ethics`, `head_excellence`, `head_compassion`, `head_humility`.
    - **Kompetensi Unit Head**: `unit_directiveness`, `unit_monitoring`, `unit_teamwork`, `unit_quality_customer`, `unit_continuous_improvement`, `unit_personal_integrity`.
    - **Kompetensi Staff**: `staff_leads_courageously`, `staff_coach_develop`, `staff_teamwork`, `staff_trouble_shooting`, `staff_customer_satisfaction`, `staff_cross_functional`, `staff_quality_focus`, `staff_continuous_improvement`, `staff_planning_monitoring`, `staff_personal_integrity`, `staff_drive_for_result`, `staff_learning`.

#### F. Pelatihan, Sertifikasi, & Karir
11. **`training`**: Target pelatihan individu per tahun: `target_mandatory`, `target_skill`, `target_managerial`, `evaluasi` (`eva1`, `eva2`, `eva3`).
12. **`riwayat_training`**: Realisasi pelatihan: `kategori` (`mandatory`, `skill`, `managerial`), `nama_training`, `status_training` (`completed`, `in_progress`, `remedial`, `not_started`), `tanggal_training`, `tingkat_evaluasi` (`eva1`, `eva2`, `eva3`), `lampiran_training`.
13. **`sertifikasi`**: Lisensi dan sertifikasi keahlian: `nama_sertifikasi`, `penerbit`, `terbit`, `kadaluarsa`, `status_sertifikat` (`aktif`, `nonaktif`), `lampiran_sertifikasi`.
14. **`perjalanan_karir`**: Rekam jejak pencapaian dan keputusan suksesi karir:
    - Data Karir: `tahun_karir`, `nama_posisi`, `tipe_milestone` (`aktif`, `nonaktif`), `status_pencapaian` (`aktif/tercapai`, `proyeksi/target`), `catatan_detail`, `operational_award`, `catatan_rekomendasi_atasan`.
    - Keputusan Manajemen: `keputusan` (Enum: `Promote to Next Grade`, `Ready for Promotion`, `Fit for Current Role`, `Extend Contract`, `Needs Further Development`, `Recommend for Rotation`, `Performance Improvement Plan`, `Terminate`).

---

## 7. Spesifikasi Fitur Fungsional Per Modul

### 7.1. Modul 1: Autentikasi & Manajemen Departemen
- **FR-AUTH-01 (Multi-Factor Department Login)**: Pengguna login menggunakan kombinasi `email`, `password`, dan pemilihan `id_departemen` yang valid untuk memvalidasi otorisasi divisional.
- **FR-AUTH-02 (Role Redirection & Gate)**:
  - Role `head` dialihkan ke `/head` (`head.dashboard`).
  - Role `admin` dialihkan ke `/admin` (`admin.dashboard`).
  - Role `user`/`staff` dialihkan ke `/user` (`user.dashboard`).
  - Middleware `EnsureUserRole` memverifikasi kesesuaian role dengan penolakan HTTP 403 Forbidden bila otorisasi tidak sesuai.
- **FR-AUTH-03 (Single Sign-On Preparation)**: Antarmuka login menyediakan tombol integrasi SSO Google untuk implementasi tahap selanjutnya.

### 7.2. Modul 2: Digital Employee Profile & Talent Matrix
- **FR-EMP-01 (Ringkasan Profil 360°)**: Menampilkan data identitas, NPK, riwayat pendidikan, kontak darurat, lama masa kerja, grade, dan posisi saat ini.
- **FR-EMP-02 (Matriks HAV 9-Box)**: Visualisasi posisi karyawan pada kuadran 9-Box *Human Asset Value* (`Star`, `High Potential`, `Potential`, `Excellent Career Person`, `Career Person`, `Problem Employee`, `Deadwood`).
- **FR-EMP-03 (Tingkat Kesiapan Suksesi - Readiness)**: Indikator kesiapan suksesi dari `R1` (siap < 6 bulan), `R2`, `R3`, `R4`, `R5` hingga `Not Ready`.
- **FR-EMP-04 (Profil Karakter & Neuroleadership)**:
  - Tipe kepribadian DISC.
  - Kartu grafik SCARF (Status, Certainty, Autonomy, Relatedness, Fairness).
  - Indikator VIA Character (Kekuatan yang berlebihan dan kekuatan yang kurang dimanfaatkan).
  - Ringkasan kekuatan (*Strength*) dan kelemahan (*Weakness*).

### 7.3. Modul 3: Laporan Asesmen & Radar Kompetensi
- **FR-ASM-01 (Multi-Year Historical Assessment)**: Penyimpanan dan perbandingan skor asesmen dari berbagai provider dan periode tahun.
- **FR-ASM-02 (Grafik Radar / Spider Chart)**: Visualisasi grafik radar interaktif untuk 4 pilar kompetensi:
  1. Kemampuan Berpikir (4 indikator)
  2. Sikap Kerja (4 indikator)
  3. Kepribadian (6 indikator)
  4. Keahlian Manajerial (9 indikator)
- **FR-ASM-03 (Gap Analysis & Benchmark)**: Fitur membandingkan skor aktual karyawan terhadap standar kompetensi minimal jabatan (*job benchmark profile*).

### 7.4. Modul 4: Individual Development Plan (IDP)
- **FR-IDP-01 (Perencanaan & Penetapan Sasaran)**: Penyusunan fokus pengembangan tahunan bersama atasan/mentor.
- **FR-IDP-02 (Siklus Pemantauan Tiga Tahap)**: Pencatatan tanggal Target Awal, Tanggal Monitoring Tengah Tahun, dan Evaluasi Final Akhir Tahun.
- **FR-IDP-03 (Penilaian Kompetensi Spesifik Berdasarkan Level)**: Form input penilaian adaptif:
  - Level Head: 9 kompetensi manajerial puncak.
  - Level Unit Head: 6 kompetensi kepemimpinan operasional.
  - Level Staff: 12 kompetensi teknis dan profesionalitas dasar.
- **FR-IDP-04 (Status Workflow)**: Manajemen status persetujuan IDP: `pending` → `completed` / `cancelled`.

### 7.5. Modul 5: Mentoring & Coaching (GROW Engine)
- **FR-MNT-01 (Penjadwalan & Log Mentoring)**: Formulir pencatatan sesi pertemuan antara mentor dan mentee.
- **FR-MNT-02 (Formulir Terstruktur Framework GROW)**:
  - *Goal*: Apa sasaran spesifik yang ingin diraih oleh mentee?
  - *Reality*: Di mana posisi dan kendala mentee saat ini?
  - *Options*: Apa saja alternatif solusi dan opsi tindakan yang mungkin dilakukan?
  - *Wrap-up*: Tindakan konkret mana yang dipilih, kapan deadline-nya, dan apa indikator keberhasilannya?
  - *Insight*: Apa pemahaman baru (*breakthrough*) yang diperoleh mentee setelah sesi?
- **FR-MNT-03 (Pelacakan Komitmen)**: Log riwayat komitmen aksi yang dapat dicek silang pada sesi monitoring berikutnya.

### 7.6. Modul 6: Pelatihan & Kirkpatrick Evaluation
- **FR-TRN-01 (Target vs Realisasi Pelatihan)**: Menghitung persentase pemenuhan target tahunan untuk kategori Mandatory, Skill, dan Managerial.
- **FR-TRN-02 (Log Riwayat Pelatihan)**: Pendaftaran keikutsertaan pelatihan dengan tanggal pelaksanaan, penyelenggara, dan status kelulusan (`completed`, `in_progress`, `remedial`, `not_started`).
- **FR-TRN-03 (Evaluasi Tiga Tingkat)**:
  - Eva 1 (*Reaction*): Kepuasan terhadap materi dan instruktur.
  - Eva 2 (*Learning*): Peningkatan pemahaman/post-test.
  - Eva 3 (*Behavior*): Penerapan perilaku kerja nyata pasca training.
- **FR-TRN-04 (Unggah Sertifikat & Bukti Kehadiran)**: Fitur unggah file sertifikat training (PDF/JPG/PNG).

### 7.7. Modul 7: Manajemen Sertifikasi Profesional
- **FR-CRT-01 (Katalog Sertifikasi Karyawan)**: Pencatatan lisensi sertifikasi, lembaga penerbit, tanggal terbit, dan masa berlaku.
- **FR-CRT-02 (Pemberitahuan Kadaluarsa Otomatis)**: Indikator visual sertifikat `aktif` vs `nonaktif` serta penanda sertifikasi yang mendekati masa tenggang kadaluarsa (H-60 dan H-30).
- **FR-CRT-03 (Verifikasi Dokumen)**: Akses pratinjau dan unduh lampiran dokumen sertifikat resmi.

### 7.8. Modul 8: Perjalanan Karir & Pengambilan Keputusan Suksesi
- **FR-CRR-01 (Career Milestone Timeline)**: Tampilan garis waktu (*timeline*) interaktif mengenai posisi jabatan, perpindahan departemen, dan penghargaan operasional (*operational award*).
- **FR-CRR-02 (Catatan Rekomendasi Atasan)**: Form input rahasia dari Department Head mengenai potensi dan perilaku kerja karyawan.
- **FR-CRR-03 (Keputusan Formal Manajemen)**: Penetapan status keputusan karir resmi:
  - *Promote to Next Grade*
  - *Ready for Promotion*
  - *Fit for Current Role*
  - *Extend Contract*
  - *Needs Further Development*
  - *Recommend for Rotation*
  - *Performance Improvement Plan (PIP)*
  - *Terminate*

---

## 8. Kebutuhan Non-Fungsional (Non-Functional Requirements)

### 8.1. Keamanan & Kepatuhan Regulasi (Security & Privacy)
- **Kepatuhan Perlindungan Data Pribadi**: Data gaji, hasil psikotes IQ, DISC, dan catatan evaluasi karyawan diklasifikasikan sebagai data rahasia (*Confidential/Restricted*) sesuai standar kepatuhan regulasi PDP.
- **Enkripsi**: Seluruh kata sandi di-hash menggunakan algoritma Bcrypt/Argon2id standar Laravel.
- **CSRF & Protection**: Seluruh request mutasi (POST/PUT/DELETE) wajib menyertakan token CSRF.
- **Role Isolation**: Department Head hanya diizinkan melihat data karyawan yang berada di departemennya atau tim yang secara hierarki berada di bawahnya.

### 8.2. Performa & Waktu Respon
- Waktu respon pemuatan halaman dashboard tidak boleh melebihi **1.5 detik** pada koneksi standar kantor.
- Kueri basis data untuk laporan agregasi menggunakan pengindeksan (*database index*) pada `id_karyawan`, `id_departemen`, dan `tahun`.

### 8.3. Keandalan & Integritas Data
- Setiap relasi foreign key menerapkan constraint integritas referensial (menggunakan `cascadeOnDelete` atau `nullOnDelete` sesuai konteks bisnis).
- Penyimpanan file lampiran (sertifikat, foto, bukti training) tersimpan terstruktur pada direktori `storage/app/public` dengan nama file acak (*hashed filename*).

### 8.4. Aksesibilitas & Responsivitas Antarmuka
- Responsif pada resolusi desktop (1920x1080, 1366x768), tablet, dan ponsel pintar.
- Antarmuka ramah pengguna (*clean layout*) dengan kontras warna memadai sesuai pedoman WCAG AA.

---

## 9. Pedoman Desain & Identitas Visual (UI/UX Design System)

Aplikasi Radar HC mengadopsi tema profesional, segar, dan berwibawa (*Modern Enterprise Workforce Aesthetic*):

### 9.1. Palet Warna Utama
- **Forest Green** (`#17483d`): Warna primer untuk bilah navigasi, tombol utama, dan identitas kepemimpinan.
- **Mint Accent** (`#c8f0d9`): Warna aksen untuk lencana status aktif, sorotan teks, dan indikator positif.
- **Paper Background** (`#f5f8f3`): Warna latar belakang halaman yang ramah di mata.
- **Brand Cyan / Teal** (`#00b4d8` / `#11a9bc`): Warna sorotan digital journey pada halaman login dan grafik radar.
- **Neutral Dark** (`#17211f`): Warna teks utama untuk keterbacaan tajam.
- **Muted Gray** (`#72807b` / `#dce6e0`): Garis batas tabel dan teks sekunder.

### 9.2. Tipografi
- Font Utama: **Instrument Sans** / sans-serif sistem modern.
- Hirarki:
  - *Eyebrow Header*: Huruf kapital, tracking lebar (`tracking-[0.18em]`), font-weight 700.
  - *Page Title*: Text 2xl - 4xl, font-weight 800, tracking tight.
  - *Data Label*: Text sm, font-medium, warna muted.

---

## 10. Roadmap Rilis & Milestone Pengembangan

```
[Fase 1: Fondasi & Master] ---> [Fase 2: Asesmen & Radar] ---> [Fase 3: Mentoring & IDP] ---> [Fase 4: Training & Karir] ---> [Fase 5: Analitik & Otomasi]
```

### Fase 1: Fondasi Master Data & Autentikasi (Selesai / In Progress)
- [x] Migrasi skema database relasional (14 tabel inti).
- [x] Autentikasi Department-Aware Login dan otorisasi middleware.
- [ ] CRUD Master Departemen & Pengguna.
- [ ] CRUD Data Karyawan (Induk, Kontak, Jabatan, Grade, Foto).

### Fase 2: Profil Psikometri & Radar Asesmen (Target: Sprint 1 - 2)
- [ ] Antarmuka Profil Karyawan 360° (DISC, SCARF, VIA Character, Strength & Weakness).
- [ ] Visualisasi Matriks 9-Box (HAV) dan Readiness Indicator.
- [ ] Modul input Asesmen Kompetensi (KB, SK, KP, MS).
- [ ] Komponen grafik Radar Interaktif (Spider Chart).

### Fase 3: IDP & Mentoring GROW Engine (Target: Sprint 3 - 4)
- [ ] Formulir interaktif IDP & rubrik penilaian otomatis (Head, Unit, Staff).
- [ ] Modul sesi Mentoring dengan panduan langkah GROW Model.
- [ ] Sistem notifikasi tenggat waktu monitoring dan evaluasi final.

### Fase 4: Pelatihan, Sertifikasi, & Perjalanan Karir (Target: Sprint 5 - 6)
- [ ] Modul pencatatan target vs realisasi Training (Mandatory, Skill, Managerial).
- [ ] Log riwayat training & evaluasi tingkat 1-3 dengan unggah bukti sertifikat.
- [ ] Modul manajemen masa berlaku Sertifikasi Keahlian.
- [ ] Halaman Timeline Perjalanan Karir & Form Keputusan Suksesi.

### Fase 5: Reporting, Ekspor Data, & Notifikasi Lanjut (Target: Sprint 7)
- [ ] Ekspor profil karyawan dan radar kompetensi ke format PDF (*Executive Summary Brief*).
- [ ] Ekspor rekapitulasi data departemen ke spreadsheet Excel.
- [ ] Email reminder otomatis untuk jadwal monitoring IDP & masa kadaluarsa sertifikasi.

---

## 11. Metrik Keberhasilan Produk (Success Metrics / KPIs)

1. **User Engagement**: Tingkat adopsi login aktif bulanan (*Monthly Active Users*) oleh Department Head dan Unit Head > 85%.
2. **IDP Completion Rate**: Persentase siklus IDP yang dievaluasi tuntas tepat waktu pada akhir tahun mencapai minimal 90%.
3. **Data Integrity**: 100% data karyawan aktif memiliki pemetaan HAV 9-Box dan status kesiapan suksesi (*Readiness*).
4. **Administrative Efficiency**: Penurunan waktu penyusunan resume rapat suksesi talenta dari 5 hari kerja menjadi < 30 menit melalui laporan instan sistem.

---

## 12. Lampiran: Matriks Istilah & Singkatan (Glossary)

| Istilah | Kepanjangan / Makna |
| :--- | :--- |
| **HC** | *Human Capital* |
| **NPK** | Nomor Pokok Karyawan (ID unik kepegawaian) |
| **HAV** | *Human Asset Value* (Matriks pemetaan talenta 9-Box berdasarkan potensi & kinerja) |
| **DISC** | *Dominance, Influence, Steadiness, Conscientiousness* (Alat profiling kepribadian kerja) |
| **SCARF** | *Status, Certainty, Autonomy, Relatedness, Fairness* (Model neurosains untuk motivasi sosial) |
| **VIA** | *Values in Action* Character Strengths (Pemetaan kekuatan karakter intrinsik) |
| **IDP** | *Individual Development Plan* (Rencana pengembangan kompetensi individu tahunan) |
| **GROW** | *Goal, Reality, Options, Wrap-up* (Model kerangka kerja pelaksanaan coaching/mentoring) |
| **PIP** | *Performance Improvement Plan* (Program pemantauan peningkatan kinerja intensif) |
| **KB / SK / KP / MS** | Kemampuan Berpikir / Sikap Kerja / Kepribadian / *Managerial Skills* |

---

*Dokumen ini merupakan panduan resmi pengembangan produk Radar HC dan dapat diperbarui secara berkala sesuai kebutuhan strategis organisasi.*
