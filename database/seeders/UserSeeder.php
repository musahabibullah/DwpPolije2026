<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the Admin role exists (match Filament Shield super_admin)
        Role::firstOrCreate(['name' => 'Admin']);

        // Create or update the default admin user and ensure correct password/role
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                // Use Hash::make to set password reliably
                'password' => Hash::make('admin123'),
            ]
        );

        // Assign the Admin role if missing
        if (! $admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }
    }
}
