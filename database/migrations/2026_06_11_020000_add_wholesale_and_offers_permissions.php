<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddWholesaleAndOffersPermissions extends Migration
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
            'wholesale-delete',
            'offers-packages-list',
            'offers-packages-create',
            'offers-packages-edit',
            'offers-packages-delete',
        ];

        // Create permissions for both guards
        foreach ($permissions as $permissionName) {
            foreach (['web', 'admin'] as $guardName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => $guardName,
                ]);
            }
        }

        // Assign all permissions to the Super Admin role for the admin guard
        $role = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();
        if ($role) {
            $allAdminPermissions = Permission::where('guard_name', 'admin')->get();
            $role->syncPermissions($allAdminPermissions);
        }

        // Also assign to Super Admin role for web guard just in case
        $webRole = Role::where('name', 'Super Admin')->where('guard_name', 'web')->first();
        if ($webRole) {
            $allWebPermissions = Permission::where('guard_name', 'web')->get();
            $webRole->syncPermissions($allWebPermissions);
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
            'wholesale-delete',
            'offers-packages-list',
            'offers-packages-create',
            'offers-packages-edit',
            'offers-packages-delete',
        ];

        foreach ($permissions as $permissionName) {
            Permission::where('name', $permissionName)->delete();
        }
    }
}
