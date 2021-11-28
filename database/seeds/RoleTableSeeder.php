<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
          [
          'id' => 1,
          'name' => 'Super Admin',
          'company_id' => 1,
          'parent_id' => null
        ],[
          'id' => 4,
          'name' => 'Seller',
          'company_id' => null,
          'parent_id' => null
        ]
      ]);
    }
}
