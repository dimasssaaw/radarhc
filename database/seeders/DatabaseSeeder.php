<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Saat Anda sudah siap menentukan siapa saja Admin HR dan Department Head,
        // Anda bisa menambahkannya di sini, atau memanggil seeder khusus.
        // Contoh:
        // User::factory(10)->create();
    }
}
