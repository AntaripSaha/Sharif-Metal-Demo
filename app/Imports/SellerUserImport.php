<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SellerUserImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $data['user_id']    = $row['user_id'];
        $data['name']       = $row['name'];
        $data['email']      = $row['email'];
        $data['password']   = Hash::make($row['password']);
        $data['phone_no']   = $row['phone_no'];
        $data['address']    = $row['address'];
        $data['company_id'] = 2;
        $data['role_id']    = $row['role_id'];
        $data['created_by'] = Auth::id();
        $data['country_id'] = 1;

        User::createUser($data);
    }

    public function rules(): array
    {
    	$validation_array = [
            'name' => 'required'
        ];
        return $validation_array;
    }
    public function headingRow(): int
    {
        return 1;
    }
}
