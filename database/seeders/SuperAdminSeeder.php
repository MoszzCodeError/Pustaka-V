<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@smkn5surakarta.sch.id'],
            [
                'name'     => 'Super Admin Utama',
                'password' => Hash::make('admin12345'),
                'role'     => 'super_admin',
                'nis_nip'  => '199001012026011001',
                'kelas'    => 'Administrator',
            ]
        );
    }
}