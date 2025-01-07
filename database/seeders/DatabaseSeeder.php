<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ManagementSystem\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //* Users Dummy
        User::factory()->create([
            'username'              => 'supaaa~~',
            'name'                  => 'Supaaa~~',
            'email'                 => 'superadmin@gmail.com',
            'password'              => Hash::make('Ap@c0134MAuL0g!nAj4sUs@h!'),
            'is_active'             => true,
            // 'is_verification'       => true
        ]);

        User::factory()->create([
            'username'              => 'supaaa2~~',
            'name'                  => 'Supaaa2~~',
            'email'                 => 'superadmin2@gmail.com',
            'password'              => Hash::make('Ap@c0134MAuL0g!nAj4sUs@h!'),
            'is_active'             => true,
            // 'is_verification'       => true
        ]);

        Role::create([
            'role_name'             => 'superadmin',
            'display_name'          => 'Superadmin',
            'description'           => 'Role superadmin memiliki akses ke semua fitur digunakan untuk pihak developer dalam maintenance fitur.',
            'is_active'             => true,
            'type_role'             => 'superadmin'
        ]);

        DB::table('role_users')->insert([
            'role_id'               => 1,
            'user_id'               => 1,
            'is_active'             => true
        ]);

        DB::table('role_users')->insert([
            'role_id'               => 1,
            'user_id'               => 2,
            'is_active'             => true
        ]);
    }
}
