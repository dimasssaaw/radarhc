# Catatan Proyek & Arsitektur Sistem: RadarHC

Berdasarkan struktur kodingan aplikasi ini (Laravel Framework), berikut adalah penjelasan detail mengenai bahasa, pembagian tugas (arsitektur), alur kerja, hingga lapisan keamanannya:

## 1. Bahasa Pemrograman & Alat (Tech Stack)
*   **PHP (Backend):** Ini adalah bahasa utama mesin Anda. Digunakan untuk seluruh logika server, pemrosesan data, sistem keamanan, dan interaksi dengan database. Semua file berekstensi `.php` (kecuali `.blade.php`) menggunakan bahasa ini.
*   **HTML & CSS (Frontend):** Digunakan untuk menyusun kerangka, tata letak, dan desain warna halaman web.
*   **JavaScript (Frontend):** Digunakan untuk membuat elemen interaktif di halaman web (seperti pop-up, validasi form instan, atau animasi) agar terasa lebih hidup.
*   **SQL (Database):** Bahasa database yang berjalan di belakang layar. Namun, karena Anda memakai Laravel, Anda jarang menulis SQL murni, melainkan menggunakan kode PHP (Eloquent) yang akan diubah otomatis menjadi SQL oleh sistem.
*   **Blade (Templating):** Bahasa template khusus milik Laravel. Memungkinkan Anda menyisipkan variabel dan logika PHP secara rapi di dalam kode HTML (menggunakan file berakhiran `.blade.php`).

---

## 2. Struktur Kodingan (Di Bagian Apa Pakai Apa?)
Proyek ini dirancang menggunakan konsep arsitektur **MVC (Model-View-Controller)**. Ini berarti kodingan Anda dipisah-pisah berdasarkan tugasnya agar rapi:

### A. Routing (Pengarah Jalan)
*   **Lokasi File:** `routes/web.php`
*   **Bahasa:** PHP
*   **Tugas Inti:** Bertindak sebagai resepsionis. Saat pengguna mengetik URL di browser (misal: `websiteanda.com/certification`), *Route* bertugas mengecek URL tersebut dan memutuskan *Controller* mana yang harus dipanggil untuk menanganinya.

### B. Controller (Otak / Logika Bisnis)
*   **Lokasi Folder:** `app/Http/Controllers/`
*   **Bahasa:** PHP
*   **Tugas Inti:** Pusat kendali aplikasi. File ini menerima "lemparan" dari Route, memproses logika apa pun yang diminta, meminta data dari database (via Model), lalu mengirimkan hasilnya untuk ditampilkan (via View).
*   *(Contoh: Jika ada fitur sertifikasi, maka `CertificationController.php` adalah file yang bertugas mengatur alur simpan, edit, dan hapus sertifikasi).*

### C. Model (Pengurus Database)
*   **Lokasi Folder:** `app/Models/`
*   **Bahasa:** PHP
*   **Tugas Inti:** Bertindak sebagai jembatan antara aplikasi Anda dan tabel di database. Anda tidak perlu repot menulis query SQL panjang; cukup panggil Model untuk mengambil atau menyimpan data.
*   *(Contoh: Model `Certification.php` adalah cerminan dari tabel `certifications` di database Anda).*

### D. View (Tampilan Antarmuka)
*   **Lokasi Folder:** `resources/views/` (seperti yang sedang Anda kerjakan saat ini)
*   **Bahasa:** HTML, CSS, JavaScript, dan sintaks Blade (`{{ ... }}`).
*   **Tugas Inti:** Ini adalah halaman yang dilihat langsung oleh mata pengguna. Controller akan mengirimkan data mentah (misal: daftar nama) ke View, lalu View akan "menghiasnya" ke dalam bentuk tabel HTML.

---

## 3. Alur Kerja Inti (Dari Awal Sampai Akhir)
Mari kita simulasikan proses ketika pengguna ingin melihat halaman daftar sertifikasi:

1.  **Request (Permintaan):** Pengguna mengklik tombol "Lihat Sertifikasi" di browser. Ini mengirimkan permintaan URL `/certification` ke server Anda.
2.  **Route (Resepsionis):** File `web.php` menangkap URL `/certification` dan berkata, *"Oh, URL ini tugasnya `CertificationController` pada bagian `index()`."*
3.  **Middleware (Satpam Keamanan):** Sebelum masuk ke Controller, pos penjagaan (Middleware) akan mencegat: *"Tunggu, apakah pengguna ini sudah Login?"*. Jika belum, dia langsung dilempar kembali ke halaman Login. Jika sudah, silakan lewat.
4.  **Controller & Model (Proses):** `CertificationController` bekerja. Ia memanggil Model `Certification` dan berkata, *"Tolong ambilkan semua data sertifikasi dari database."* Model mengambilnya dari database dan menyerahkannya ke Controller.
5.  **View (Tampilan):** Controller mengumpulkan data tersebut dan mengirimkannya ke file `resources/views/certification/index.blade.php`.
6.  **Response (Hasil Akhir):** Laravel merakit gabungan kode Blade, HTML, CSS, dan Data tadi menjadi sebuah halaman website utuh yang cantik, lalu mengirimkannya kembali ke browser pengguna. Halaman pun tampil.

---

## 4. Keamanan Keseluruhan (Security)
Laravel sangat terkenal aman karena memiliki pelindung otomatis berlapis. Berikut sistem keamanannya dari proyek ini:

1.  **Proteksi Injeksi Database (SQL Injection):**
    *   Karena Anda menggunakan Model (Eloquent) bawaan Laravel, setiap input yang dimasukkan pengguna (misal dari form pengisian) akan "dibersihkan" secara otomatis sebelum masuk ke database. Ini mencegah peretas (hacker) merusak database Anda melalui perintah SQL jahat.
2.  **Proteksi Pemalsuan Formulir (CSRF - Cross-Site Request Forgery):**
    *   Setiap kali Anda membuat formulir pendaftaran atau penyimpanan di file `.blade.php`, Anda wajib menaruh kode `@csrf`. Ini adalah token rahasia (seperti tiket). Saat form dikirim, server akan mengecek token ini. Jika tokennya tidak cocok (berarti dikirim dari web peretas, bukan web asli Anda), permintaan akan diblokir.
3.  **Proteksi Serangan Skrip (XSS - Cross-Site Scripting):**
    *   Terkadang peretas memasukkan kode JavaScript berbahaya di kolom nama (misal: `<script>curi_password()</script>`). Namun, saat Anda menampilkan data menggunakan `{{ $data }}` di Blade, Laravel secara otomatis menetralisirnya (diubah menjadi teks biasa, bukan skrip yang bisa dieksekusi).
4.  **Validasi Data (Validation):**
    *   Di dalam Controller, sebelum data disimpan, sistem memvalidasinya secara ketat. *(Misal: "Email harus berupa @email.com yang valid, Password harus minimal 8 huruf").* File sampah tidak akan bisa masuk.
5.  **Pembatasan Hak Akses (Authentication & Authorization):**
    *   Mengatur secara ketat fitur apa saja yang boleh diakses *Guest* (Tamu), *User Biasa*, atau *Admin*. Ini diatur menggunakan **Middleware** (pos penjaga) atau **Gates/Policies** (penjaga pintu khusus per-tindakan).

File catatan ini merangkum pondasi lengkap cara aplikasi Anda bernapas dan bekerja.
