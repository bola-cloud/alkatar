<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SubscriptionPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'subscription-list',
            'subscription-create',
            'subscription-edit',
            'subscription-delete',
        ];

        // Create permissions for both 'web' and 'admin' guards (idempotent)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin',
            ]);
        }

        // Fetch created Permission models grouped by guard
        $webPermissionModels = Permission::whereIn('name', $permissions)->where('guard_name', 'web')->get();
        $adminPermissionModels = Permission::whereIn('name', $permissions)->where('guard_name', 'admin')->get();

        // Assign to any role named 'Super Admin' (handle both web/admin guards correctly)
        $superAdminRoles = Role::where('name', 'Super Admin')->get();
        if ($superAdminRoles->isNotEmpty()) {
            foreach ($superAdminRoles as $role) {
                if ($role->guard_name === 'admin') {
                    $role->givePermissionTo($adminPermissionModels);
                } else {
                    $role->givePermissionTo($webPermissionModels);
                }
            }
        }

        // Also assign to any existing admin-guard roles (name-independent)
        $adminRoles = Role::where('guard_name', 'admin')->get();
        if ($adminRoles->isNotEmpty()) {
            foreach ($adminRoles as $role) {
                $role->givePermissionTo($adminPermissionModels);
            }
        }
    }
}
