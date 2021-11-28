<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->insert([
            'id'=>1,
            'name' => 'Sheikh Md Sabbir',
            'email' => 'sabbirjoy8@gmail.com',
            'password' => Hash::make('123456'),
            'phone_no'  => '01625078910',
            'user_id'  => '1',
            'status'  => 1,
            'role_id'  => 1,
            'company_id'  => 1,
            'created_by'  => 1,
            'updated_by'  => 1,
            'country_id'  => 1
                    ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
