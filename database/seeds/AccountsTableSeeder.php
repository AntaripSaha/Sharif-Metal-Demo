<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert(
        //   [
        //   'HeadCode' => '1',
        //   'HeadName' => 'Assets',
        //   'PHeadName' => 'COA',
        //   'HeadLevel' => 0,
        //   'IsActive' => 1,
        //   'IsTransaction' => 0,
        //   'IsGL' => 0,
        //   'HeadType' => 'A',
        //   'IsBudget' => 0,
        //   'IsDepreciation' => 0,
        //   'user_id' => null,
        //   'company_id' => null,
        //   'DepreciationRate' => '0.00',
        //   'bank_id' =>null,
        //   'created_by' => 1,
        //   'updated_by' => null,
        // ],
        //   [
        //   'HeadCode' => '2',
        //   'HeadName' => 'Equity',
        //   'PHeadName' => 'COA',
        //   'HeadLevel' => 0,
        //   'IsActive' => 1,
        //   'IsTransaction' => 0,
        //   'IsGL' => 0,
        //   'HeadType' => 'L',
        //   'IsBudget' => 0,
        //   'IsDepreciation' => 0,
        //   'user_id' => null,
        //   'company_id' => null,
        //   'DepreciationRate' => '0.00',
        //   'bank_id' =>null,
        //   'created_by' => 1,
        //   'updated_by' => null,
        // ],
        // [
        //   'HeadCode' => '4',
        //   'HeadName' => 'Expence',
        //   'PHeadName' => 'COA',
        //   'HeadLevel' => 0,
        //   'IsActive' => 1,
        //   'IsTransaction' => 0,
        //   'IsGL' => 0,
        //   'HeadType' => 'E',
        //   'IsBudget' => 0,
        //   'IsDepreciation' => 0,
        //   'user_id' => null,
        //   'company_id' => null,
        //   'DepreciationRate' => '0.00',
        //   'bank_id' =>null,
        //   'created_by' => 1,
        //   'updated_by' => null,
        // ],
        // [
        //   'HeadCode' => '3',
        //   'HeadName' => 'Income',
        //   'PHeadName' => 'COA',
        //   'HeadLevel' => 0,
        //   'IsActive' => 1,
        //   'IsTransaction' => 0,
        //   'IsGL' => 0,
        //   'HeadType' => 'I',
        //   'IsBudget' => 0,
        //   'IsDepreciation' => 0,
        //   'user_id' => null,
        //   'company_id' => null,
        //   'DepreciationRate' => '0.00',
        //   'bank_id' =>null,
        //   'created_by' => 1,
        //   'updated_by' => null,
        // ],
        [
          'HeadCode' => '5',
          'HeadName' => 'Liabilities',
          'PHeadName' => 'COA',
          'HeadLevel' => 0,
          'IsActive' => 1,
          'IsTransaction' => 0,
          'IsGL' => 0,
          'HeadType' => 'L',
          'IsBudget' => 0,
          'IsDepreciation' => 0,
          'user_id' => null,
          'company_id' => null,
          'DepreciationRate' => '0.00',
          'bank_id' =>null,
          'created_by' => 1,
          'updated_by' => null,
        ]);
    }
}
