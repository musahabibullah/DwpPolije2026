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
            ['email' => 'bendahara1@bendahara.com'],
            [
                'name' => 'Bendahara 1',
                'password' => bcrypt('12345'),
            ]
        );
        $bendahara1->assignRole('bendahara');
        $bendahara1->jurusans()->syncWithoutDetaching([1, 2, 3, 4, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17]);

        // Bendahara 2
        $bendahara2 = User::firstOrCreate(
            ['email' => 'bendahara2@bendahara.com'],
            [
                'name' => 'Bendahara 2',
                'password' => bcrypt('12345'),
            ]
        );
        $bendahara2->assignRole('bendahara');
        $bendahara2->jurusans()->syncWithoutDetaching([6, 16, 18, 21]);

        // Bendahara 3
        $bendahara3 = User::firstOrCreate(
            ['email' => 'bendahara3@bendahara.com'],
            [
                'name' => 'Bendahara 3',
                'password' => bcrypt('12345'),
            ]
        );
        $bendahara3->assignRole('bendahara');
        $bendahara3->jurusans()->syncWithoutDetaching([5, 19, 20, 22, 23, 24, 25, 26, 27]);
    }
}
