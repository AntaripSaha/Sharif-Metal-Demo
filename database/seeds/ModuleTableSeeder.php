<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            [
                'id' =>1,
                'name' => 'users',
                'slug' =>'users',
                'display_name' => 'User Module',
                'model_name' => 'App\User',
                'policy_name' =>null
            ], [
                'id' =>2,
                'name' => 'roles',
                'slug' =>'roles',
                'display_name' => 'Role Module',
                'model_name' => 'Modules\Role\Entities\Role',
                'policy_name' =>null
            ],
            [
                'id' =>3,
                'name' => 'product',
                'slug' =>'product',
                'display_name' => 'Product Module',
                'model_name' => 'Modules\Product\Entities\Product',
                'policy_name' =>null
            ],
            [
                'id' =>4,
                'name' => 'warehouse',
                'slug' =>'warehouse',
                'display_name' => 'Warehouse Module',
                'model_name' => 'Modules\Warehouse\Entities\Warehouse',
                'policy_name' =>null
            ],
            [
                'id' =>5,
                'name' => 'bank',
                'slug' =>'bank',
                'display_name' => 'Bank Module',
                'model_name' => 'Modules\Bank\Entities\Bank',
                'policy_name' =>null
            ],
            [
                'id' =>6,
                'name' => 'customer',
                'slug' =>'customer',
                'display_name' => 'Customer Module',
                'model_name' => 'Modules\Customer\Entities\Customer',
                'policy_name' =>null
            ],
            [
                'id' =>7,
                'name' => 'seller',
                'slug' =>'seller',
                'display_name' => 'Seller Module',
                'model_name' => 'Modules\Seller\Entities\Seller',
                'policy_name' =>null
            ],
            [
                'id' =>8,
                'name' => 'accounts',
                'slug' =>'accounts',
                'display_name' => 'Accounts Module',
                'model_name' => 'Modules\Accounts\Entities\Accounts',
                'policy_name' =>null
            ],[
                'id' =>9,
                'name' => 'supplier',
                'slug' =>'supplier',
                'display_name' => 'Supplier Module',
                'model_name' => 'Modules\Supplier\Entities\Supplier',
                'policy_name' =>null
            ],[
                'id' =>10,
                'name' => 'reports',
                'slug' =>'reports',
                'display_name' => 'Reports Module',
                'model_name' => 'Modules\Reports\Entities\Report',
                'policy_name' =>null
            ],
            [
                'id' =>11,
                'name' => 'fiscalyears',
                'slug' =>'fiscalyears',
                'display_name' => 'Fiscal Years Module',
                'model_name' => 'Modules\FiscalYears\Entities\FiscalYears',
                'policy_name' =>null
            ],
            [
                'id' =>12,
                'name' => 'activitylog',
                'slug' =>'activitylog',
                'display_name' => 'Activity Log',
                'model_name' => 'Modules\ActivityLog\Entities\ActivityLog',
                'policy_name' =>null
            ]
        ]);
    }
}
