<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission

        // Tambahkan akun mahasiswa kedua
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Rizky Ramadhan',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        // Tambahkan mata kuliah kedua
        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF302'],
            [
                'nama_mk' => 'Basis Data',
                'deskripsi' => 'Mempelajari perancangan dan pengelolaan database menggunakan SQL.',
                'sks' => 3,
                'semester' => 'Genap 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        // Tambahkan mata kuliah ketiga
        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'nama_mk' => 'Pemrograman Berorientasi Objek',
                'deskripsi' => 'Mempelajari konsep class, object, inheritance, encapsulation, dan polymorphism.',
                'sks' => 2,
                'semester' => 'Ganjil 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        // Daftarkan mahasiswa pada mata kuliah
        $course1->students()->syncWithoutDetaching([
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $course3->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        // Tambahkan tugas pertama
        $assignment1 = Assignment::updateOrCreate(
            [
                'course_id' => $course1->id,
                'judul' => 'Tugas Arsitektur MVC',
            ],
            [
                'deskripsi' => 'Jelaskan konsep dan cara kerja arsitektur MVC pada Laravel.',
                'deadline' => now()->addDays(7),
            ]
        );

        // Tambahkan tugas kedua
        $assignment2 = Assignment::updateOrCreate(
            [
                'course_id' => $course2->id,
                'judul' => 'Tugas Perancangan Database',
            ],
            [
                'deskripsi' => 'Buat rancangan database sederhana menggunakan ERD.',
                'deadline' => now()->addDays(14),
            ]
        );

        // Tambahkan submission mahasiswa pertama
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'user_id' => $mahasiswa1->id,
            ],
            [
                'jawaban' => 'MVC terdiri dari Model, View, dan Controller.',
                'nilai' => 90,
                'catatan_dosen' => 'Jawaban sudah baik dan sesuai materi.',
                'submitted_at' => now(),
            ]
        );

        // Tambahkan submission mahasiswa kedua
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'user_id' => $mahasiswa2->id,
            ],
            [
                'jawaban' => 'MVC merupakan pola arsitektur pengembangan aplikasi.',
                'nilai' => null,
                'catatan_dosen' => null,
                'submitted_at' => now(),
            ]
        );

        // Tambahkan submission pada tugas kedua
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment2->id,
                'user_id' => $mahasiswa1->id,
            ],
            [
                'jawaban' => 'Rancangan database terdiri dari entitas, atribut, dan relasi.',
                'nilai' => 85,
                'catatan_dosen' => 'Rancangan sudah cukup baik.',
                'submitted_at' => now(),
            ]
        );
    }
}
