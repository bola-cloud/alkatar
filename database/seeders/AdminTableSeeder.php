<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create or update admin by email to avoid duplicate key errors
        $admin = Admin::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'image' => 'profile.png',
                'is_admin' => 1,
                'password' => Hash::make('password'),
            ]
        );

        // Ensure a role exists for Super Admin with the 'admin' guard
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);

        // Get permissions for the 'admin' guard. If none exist, clone 'web' permissions for admin guard.
        $allPermissions = Permission::where('guard_name', 'admin')->pluck('name')->toArray();
        if (empty($allPermissions)) {
            $webPermissions = Permission::where('guard_name', 'web')->pluck('name')->toArray();
            foreach ($webPermissions as $p) {
                Permission::firstOrCreate(['name' => $p, 'guard_name' => 'admin']);
            }
            $allPermissions = Permission::where('guard_name', 'admin')->pluck('name')->toArray();
        }

        if (! empty($allPermissions)) {
            $role->syncPermissions($allPermissions);
        }

        // Assign role to the admin user
        if ($admin && ! $admin->hasRole($role->name)) {
            $admin->assignRole($role->name);
        }
    }
}
