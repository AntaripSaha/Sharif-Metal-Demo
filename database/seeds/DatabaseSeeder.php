<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
         	//UserSeeder::class,
         	//CompanySeeder::class,
         	//CountryTableSeeder::class,
            MenuItemTableSeeder::class,
         	//MenusTableSeeder::class,
         	// ModuleTableSeeder::class,
            // PermissionsTableSeeder::class,
            //RoleTableSeeder::class,
            //AccountsTableSeeder::class,
        ]);
    }
}
