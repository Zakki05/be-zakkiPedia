<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'nama_role' => 'Super Admin',
        ]);

        Role::create([
            'nama_role' => 'Admin',
        ]);

        Role::create([
            'nama_role' => 'Pelanggan',
        ]);

        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'nama' => 'admin',
            'alamat' => 'indonesia',
            'role_id' => '1',
        ]);
    }
}
