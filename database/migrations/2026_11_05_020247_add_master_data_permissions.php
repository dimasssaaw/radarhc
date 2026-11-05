<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'lihat_master_idp',
            'tambah_master_idp',
            'edit_master_idp',
            'hapus_master_idp',
            'lihat_master_assessment',
            'tambah_master_assessment',
            'edit_master_assessment',
            'hapus_master_assessment',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'lihat_master_idp',
            'tambah_master_idp',
            'edit_master_idp',
            'hapus_master_idp',
            'lihat_master_assessment',
            'tambah_master_assessment',
            'edit_master_assessment',
            'hapus_master_assessment',
        ];

        Permission::whereIn('name', $permissions)->delete();
    }
};
