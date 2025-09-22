<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    public function run(): void
    {
        // ============ CREATE SUPER ADMIN ============
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@eretribusi.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('superadmin123'),
                'email_verified_at' => now()
            ]
        );
        
        // Assign role super-admin
        $superAdmin->assignRole('super-admin');
        echo "✅ Super Admin created: {$superAdmin->email}\n";

        // ============ CREATE ADMIN REGIONAL ============
        $admin = User::firstOrCreate(
            ['email' => 'admin@eretribusi.com'],
            [
                'name' => 'Administrator Regional',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now()
            ]
        );
        
        // Assign role admin
        $admin->assignRole('admin');
        echo "✅ Admin created: {$admin->email}\n";

        // ============ CREATE SAMPLE USER ============
        $user = User::firstOrCreate(
            ['email' => 'user@perusahaan.com'],
            [
                'name' => 'Pemilik Perusahaan',
                'password' => Hash::make('user123'),
                'email_verified_at' => now()
            ]
        );
        
        // Assign role user
        $user->assignRole('user');
        echo "✅ Sample User created: {$user->email}\n";

        echo "\n📋 Default Login Credentials:\n";
        echo "Super Admin: superadmin@eretribusi.com / superadmin123\n";
        echo "Admin: admin@eretribusi.com / admin123\n";
        echo "User: user@perusahaan.com / user123\n";
    }
}