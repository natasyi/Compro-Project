<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission list
        $permissions = [
            'manage statistics',
            'manage products',
            'manage principles',
            'manage testimonials',
            'manage clients',
            'manage teams',
            'manage about',
            'manage appointments',
            'manage hero selection',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Design Manager role
        $designManagerRole = Role::firstOrCreate(['name' => 'design manager']);
        $designManagerPermissions = [
            'manage products',
            'manage principles',
            'manage testimonials',
        ];
        $designManagerRole->syncPermissions($designManagerPermissions);

        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Create default super admin user
        $user = User::firstOrCreate([
            'email' => 'super@admin.com',
        ], [
            'name' => 'ComproProject',
            'password' => bcrypt('123123123'),
        ]);

        // Assign super admin role to the user
        $user->assignRole($superAdminRole);
    }
}
