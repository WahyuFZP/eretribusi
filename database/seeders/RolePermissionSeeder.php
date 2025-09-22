<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============ CREATE PERMISSIONS ============
        $permissions = [
            // Company Management
            'companies.view-all',        // Super Admin & Admin bisa lihat semua perusahaan
            'companies.view-own',        // User bisa lihat perusahaan sendiri
            'companies.create',          // Admin & Super Admin bisa buat perusahaan baru
            'companies.edit',            // Edit data perusahaan
            'companies.delete',          // Delete perusahaan
            
            // Retribution Types Management
            'retribution-types.view',    // Lihat jenis retribusi
            'retribution-types.create',  // Buat jenis retribusi baru
            'retribution-types.edit',    // Edit jenis retribusi
            'retribution-types.delete',  // Hapus jenis retribusi
            
            // Bills Management
            'bills.view-all',            // Lihat semua tagihan (Admin/Super Admin)
            'bills.view-own',            // Lihat tagihan sendiri (User)
            'bills.create',              // Buat tagihan baru
            'bills.edit',                // Edit tagihan
            'bills.delete',              // Hapus tagihan
            
            // Payment Management
            'payments.view-all',         // Lihat semua pembayaran
            'payments.view-own',         // Lihat pembayaran sendiri
            'payments.create',           // Input pembayaran
            'payments.verify',           // Verifikasi pembayaran (Admin only)
            'payments.reject',           // Tolak pembayaran
            
            // User Management (Super Admin only)
            'users.view',                // Lihat daftar user
            'users.create',              // Buat user baru
            'users.edit',                // Edit data user
            'users.delete',              // Hapus user
            'users.assign-roles',        // Assign role ke user
            
            // Reports
            'reports.view',              // Lihat laporan
            'reports.export',            // Export laporan
            
            // System Settings
            'settings.view',             // Lihat pengaturan sistem
            'settings.edit',             // Edit pengaturan sistem
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ============ CREATE ROLES ============
        
        // 1. SUPER ADMIN - Full Access
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all()); // Give all permissions

        // 2. ADMIN - Regional Management
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            // Company management
            'companies.view-all',
            'companies.create',
            'companies.edit',
            
            // Retribution types
            'retribution-types.view',
            'retribution-types.create',
            'retribution-types.edit',
            
            // Bills management  
            'bills.view-all',
            'bills.create',
            'bills.edit',
            
            // Payment verification
            'payments.view-all',
            'payments.verify',
            'payments.reject',
            
            // Basic user view
            'users.view',
            
            // Reports
            'reports.view',
            'reports.export',
        ]);

        // 3. USER - Company Owner
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions([
            // Own company only
            'companies.view-own',
            'companies.edit',
            
            // Own bills
            'bills.view-own',
            
            // Own payments
            'payments.view-own',
            'payments.create',
            
            // Basic reports
            'reports.view',
        ]);

        echo "✅ Roles dan Permissions berhasil dibuat!\n";
        echo "📝 Super Admin: " . $superAdmin->permissions->count() . " permissions\n";
        echo "📝 Admin: " . $admin->permissions->count() . " permissions\n"; 
        echo "📝 User: " . $user->permissions->count() . " permissions\n";
    }
}