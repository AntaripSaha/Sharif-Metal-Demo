<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('countries')->insert([
            [
                'id' => 1,
                'country_code' => 88,
                'name' =>'Bangladesh',
                'min_digits' => 11,
                'max_digits' => 11,
                'iso' => 'BD',
                'lang_name'=>'en',
                'lang_local'=>'en'
            ]
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
