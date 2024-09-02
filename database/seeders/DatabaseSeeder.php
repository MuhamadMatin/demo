<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin'),
                // 'roles' => 'super_admin'
            ],
            [
                'name' => 'ketua',
                'email' => 'ketua@gmail.com',
                'password' => bcrypt('ketua'),
                // 'roles' => 'KETUA'
            ],
            [
                'name' => 'wakil',
                'email' => 'wakil@gmail.com',
                'password' => bcrypt('wakil'),
                // 'roles' => 'WAKIL'
            ],
            [
                'name' => 'keuangan',
                'email' => 'keuangan@gmail.com',
                'password' => bcrypt('keuangan'),
                // 'roles' => 'KEUANGAN'
            ],
            [
                'name' => 'sumberdayamanusia',
                'email' => 'sumberdayamanusia@gmail.com',
                'password' => bcrypt('sumberdayamanusia'),
                // 'roles' => 'SDM'
            ],
            [
                'name' => 'administrasi',
                'email' => 'administrasi@gmail.com',
                'password' => bcrypt('administrasi'),
                // 'roles' => 'ADMINISTRASI'
            ],
            [
                'name' => 'akademik',
                'email' => 'akademik@gmail.com',
                'password' => bcrypt('akademik'),
                // 'roles' => 'AKADEMIK'
            ],
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => bcrypt('user'),
                // 'roles' => 'USER'
            ],
        ];

        User::insert($user);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
