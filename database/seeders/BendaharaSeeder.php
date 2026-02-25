<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class BendaharaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Pastikan role bendahara sudah ada
        Role::firstOrCreate(['name' => 'bendahara', 'guard_name' => 'web']);

        // Bendahara 1
        $bendahara1 = User::firstOrCreate(
            ['email' => 'bendahara@bendahara.com'],
            [
                'name' => 'Bendahara',
                'password' => bcrypt('12345'),
            ]
        );
        $bendahara1->assignRole('bendahara');
        $bendahara1->jurusans()->syncWithoutDetaching([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27]);
    }
}
