<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Safety: hanya jalankan di environment local/dev
        if (!app()->environment('local')) {
            $this->command->info('FakeUsersSeeder skipped: not in local environment.');
            return;
        }

        // Optional: konfirmasi interaktif saat dijalankan manual
        if (!$this->command->confirm('Create 50 fake users? (yes/no)')) {
            $this->command->info('Aborted FakeUsersSeeder.');
            return;
        }

        // Pastikan role sudah tersedia
        $roles = Role::pluck('name')->toArray();
        if (empty($roles)) {
            $this->command->info('No roles found. Run RolePermissionSeeder first.');
            return;
        }

        $count = 15;

        User::factory()
            ->count($count)
            ->create()
            ->each(function ($user) use ($roles) {
                $allowed = array_filter($roles, fn($role) => $role !== 'super-admin');
                
                // Contoh probabilitas: 5% admin, sisanya user
                if (in_array('admin', $allowed) && rand(1,100) <= 5) {
                    $user->assignRole('admin');
                } else {
                    // Assign random non-admin role (bias ke 'user' jika ada)
                    if (in_array('user', $allowed)) {
                        $user->assignRole('user');
                    } else {
                        // fallback: assign any allowed role
                        $user->assignRole($allowed[array_rand($allowed)]);
                    }
                }
                
            });
            $this->command->info("Created {$count} fake users and assigned roles.");
    }
}
