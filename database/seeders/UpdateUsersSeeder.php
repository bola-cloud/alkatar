<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')
            ->where('email', 'admin@gmail.com')
            ->update([
                'password' => Hash::make('12345678'),
            ]);

        // DB::table('users')
        //     ->where('email', 'dev@gmail.com')
        //     ->update([
        //         'password' => Hash::make('F7$pK1&wD6nZ3^hR'),
        //     ]);
    }
}
