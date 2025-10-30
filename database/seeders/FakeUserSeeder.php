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

        $count = 15; // jumlah fake users

        // Optional: konfirmasi interaktif saat dijalankan manual
        // Otomatis dilewati jika menjalankan dengan --no-interaction atau ENV SEED_SKIP_CONFIRM=true
        $skipConfirm = false;
        try {
            $skipConfirm = (bool) ($this->command->option('no-interaction') ?? false) || (bool) env('SEED_SKIP_CONFIRM', false);
        } catch (\Throwable $e) {
            // ignore if option not available
        }

        if (!$skipConfirm) {
            if (!$this->command->confirm("Create {$count} fake users? (yes/no)")) {
                $this->command->info('Aborted FakeUsersSeeder.');
                return;
            }
        }

        // Pastikan role sudah tersedia
        $roles = Role::pluck('name')->toArray();
        if (empty($roles)) {
            $this->command->info('No roles found. Run RolePermissionSeeder first.');
            return;
        }

        // Generate users first
        $users = User::factory()->count($count)->create();

        // Determine exact 5% admins (rounded) with minimum 1 when count > 0
        $allowed = array_values(array_filter($roles, fn ($role) => $role !== 'super-admin'));
        $hasAdminRole = in_array('admin', $allowed, true);
        $hasUserRole = in_array('user', $allowed, true);

        $adminCount = 0;
        if ($hasAdminRole) {
            $adminCount = (int) round($count * 0.05);
            if ($count > 0 && $adminCount === 0) {
                $adminCount = 1; // ensure at least 1 admin for small samples
            }
            $adminCount = min($adminCount, $count);
        }

        $adminSample = $hasAdminRole && $adminCount > 0
            ? $users->random($adminCount)
            : collect();

        foreach ($users as $user) {
            if ($hasAdminRole && $adminSample->contains('id', $user->id)) {
                $user->assignRole('admin');
                continue;
            }

            if ($hasUserRole) {
                $user->assignRole('user');
            } else {
                // fallback: assign any allowed role
                if (!empty($allowed)) {
                    $user->assignRole($allowed[array_rand($allowed)]);
                }
            }
        }

        $this->command->info("Created {$count} fake users. Admins: "
            . ($hasAdminRole ? $adminSample->count() : 0)
            . ", Users: " . ($hasUserRole ? ($count - ($adminSample->count())) : 0));
    }
}
