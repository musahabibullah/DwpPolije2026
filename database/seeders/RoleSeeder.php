<?php
// 1. Buat seeder untuk roles (database/seeders/RoleSeeder.php)
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Buat role super admin
        $superAdmin = Role::create(['name' => 'super_admin']);
        
        // Assign semua permission ke super admin
        $permissions = Permission::all();
        $superAdmin->syncPermissions($permissions);
    }
}
