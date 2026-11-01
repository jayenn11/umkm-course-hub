<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@umkm.id'],
            [
                'name' => 'Admin UMKM Hub',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nama_usaha' => null,
            ]
        );

        // 2. Akun Peserta
        User::firstOrCreate(
            ['email' => 'budi@umkm.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'nama_usaha' => 'Kopi Cingcin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'siti@umkm.id'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'nama_usaha' => 'Keripik Berkah',
            ]
        );

        // 3. Panggil Seeder Kategori, 6 Kursus Komprehensif, dan Materi Pembelajaran
        $this->call(CourseContentSeeder::class);
    }
}
