<?php

namespace App\Imports;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Entities\Customer;
use Modules\Accounts\Entities\Accounts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $partyInfo = explode("_", $row['party_info']);
        $customer_code = $partyInfo[0];
        if(count($partyInfo) == 2){
            $customer_name = $partyInfo[1];
        }else{
            $customer_name = $customer_code;
        }

        $data['customer_id']     = $customer_code;
        $data['customer_name']   = $customer_name;
        $data['seller_id']       = $row['seller_id'];
        $data['status']          = 2;
        $data['created_by']      = Auth::id();

        $customer = Customer::createCustomer($data);
        $customer_id = $customer->id;
        // $coa = Accounts::where('HeadLevel',4)->where('HeadCode','Like','1020300'.'%')->latest()->first();
        $coa = Accounts::where('HeadLevel',4)->where('HeadCode','Like','10201'.'%')->orderBy('HeadCode', 'desc')->first();

            if ($coa) {
                $num = $coa['HeadCode'];
                $int = (int)$num;
                $headcode=$int+1;
            }
            else{
                $headcode="102010000001";

            }
            // $c_acc=$customer_id.'-'.$row['customer_name'];
            $c_acc=$customer_id.'-'.$customer_name;

            $created_by=Auth::id();
            $customer_coa['HeadCode']           = $headcode;
            $customer_coa['HeadName']           = $c_acc;
            $customer_coa['PHeadCode']          = 10201;
            $customer_coa['PHeadName']          = 'Trade Receivable';
            $customer_coa['HeadLevel']          = '4';
            $customer_coa['IsActive']           = '1';
            $customer_coa['IsTransaction']      = '1';
            $customer_coa['IsGL']               = '0';
            $customer_coa['HeadType']           = 'A';
            $customer_coa['IsBudget']           = '0';
            $customer_coa['IsDepreciation']     = '0';
            $customer_coa['DepreciationRate']   = '0';
            $customer_coa['customer_id']        = $customer_id;
            $customer_coa['created_by']         = Auth::id();
            Accounts::createAccounts($customer_coa);
            if ($row['balance']) {
                Customer::previous_balance_add($row['balance'],$customer_id);
            }
        return $customer;
    }
    public function rules(): array
    {
    	$validation_array = [
            'party_info' => 'required'
        ];
        return $validation_array;
    }
    public function headingRow(): int
    {
        return 1;
    }
}
