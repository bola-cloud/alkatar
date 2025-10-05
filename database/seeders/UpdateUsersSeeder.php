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
        DB::table('users')
            ->where('email', 'admin@gmail.com')
            ->update([
                'password' => Hash::make('T9!vG4#rQ2zM8@xL'),
            ]);

        DB::table('users')
            ->where('email', 'dev@gmail.com')
            ->update([
                'password' => Hash::make('F7$pK1&wD6nZ3^hR'),
            ]);
    }
}
