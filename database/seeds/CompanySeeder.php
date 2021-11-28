<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('companies')->insert([
            [
                'id' => 1,
                'name' => 'Zaman-It',
                'phone_no' =>'123456789',
                'company_no' =>'1',
                'phone_code' => 88,
                'address' => 'Uttara-sector-10,Dhaka',
                'updated_by'=> 1,
                'created_by'=> 1,
                'status'=> 1,
                'city' => 'Dhaka',
                'postal_code' => '1229',
                'country_id' => 1,
                'parent_id' => 0,
                'created_at' => Carbon::now()
            ]
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
