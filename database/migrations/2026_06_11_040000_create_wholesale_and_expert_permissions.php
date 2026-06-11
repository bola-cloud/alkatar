<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateWholesaleAndExpertPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'wholesale-list',
            'wholesale-create',
            'wholesale-edit',
            'wholesale-delete',
            'expert-request-list',
            'expert-request-create',
            'expert-request-edit',
            'expert-request-delete',
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

        // Assign to Super Admin roles
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

        // Also assign to any existing admin-guard roles
        $adminRoles = Role::where('guard_name', 'admin')->get();
        if ($adminRoles->isNotEmpty()) {
            foreach ($adminRoles as $role) {
                $role->givePermissionTo($adminPermissionModels);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permissions = [
            'wholesale-list',
            'wholesale-create',
            'wholesale-edit',
            'wholesale-delete',
            'expert-request-list',
            'expert-request-create',
            'expert-request-edit',
            'expert-request-delete',
        ];

        Permission::whereIn('name', $permissions)->delete();
    }
}
