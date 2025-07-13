<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
        'name' => 'Administrator',
        'email' => 'admin@peminjaman-arsip.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'phone' => '081234567890',
        'address' => 'Jl. Kearsipan No. 1, Jakarta',
        'status' => 'active',
        ]);

        // Sample peminjam users
        User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
        'role' => 'peminjam',
        'phone' => '081234567891',
        'address' => 'Jl. Contoh No. 2, Jakarta',
        'status' => 'active',
        ]);

        User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'password' => Hash::make('password'),
        'role' => 'peminjam',
        'phone' => '081234567892',
        'address' => 'Jl. Sample No. 3, Jakarta',
        'status' => 'active',
        ]);
    }
}
