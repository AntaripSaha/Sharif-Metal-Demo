<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Entities\Customer;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
use App\User;
use Auth;

class Customer extends Model
{
    protected $fillable = ['customer_id','seller_id','customer_name','customer_address','address2','customer_mobile','customer_email','contact','phone','city','state','zip','country','company_id','status','created_by','created_at','updated_at'];

    public function buys()
    {
        return $this->hasMany('\Modules\Seller\Entities\SellRequest','customer_id','id');        
    }

    public static function createCustomer($requestData){
        try{
            $id = static::create($requestData);
            return $id;

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }

    public static function updateCustomer($requestData, $customer_id){
        try{
            $customer = static::find($customer_id);
            $customer->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }

    public function accounts()
    {
        return $this->belongsTo(Accounts::class,'id','customer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class,'seller_id','id');
    }

    public static function previous_balance_add($balance,$cus_id)
    {
        $customer = Customer::find($cus_id);
        $headn = $cus_id.'-'.$customer->customer_name;
        $coainfo = Accounts::where('HeadName',$headn)->first();
        $customer_headcode = $coainfo->HeadCode;
        $transaction_id = generateRandomStr(10);

        // Customer debit for previous balance

        $cosdr = array(
            'VNo'            =>  $transaction_id,
            'Vtype'          =>  'PR Balance',
            'VDate'          =>  date("Y-m-d"),
            'COAID'          =>  $customer_headcode,
            'Narration'      =>  'Customer debit For '.$customer->customer_name,
            'Debit'          =>  $balance,
            'Credit'         =>  0,
            'IsPosted'       => 1,
            'created_by'     => Auth::user()->id,
            'updated_by'     => Auth::user()->id,
            'IsAppove'       => 1,
            'company_id'     => Auth::user()->company_id
            );
       $inventory = array(
            'VNo'            => $transaction_id,
            'Vtype'          => 'PR Balance',
            'VDate'          => date("Y-m-d"),
            'COAID'          => 10301,
            'Narration'      => 'Inventory credit For Old sale For '.$customer->customer_name,
            'Debit'          => 0,
            'Credit'         => $balance,//previous price asbe
            'IsPosted'       => 1,
            'created_by'     => Auth::user()->id,
            'updated_by'     => Auth::user()->id,
            'IsAppove'       => 1,
            'company_id'     => Auth::user()->company_id
            ); 

            Transactions::createTransaction($cosdr);
            Transactions::createTransaction($inventory);

        // if(!empty($balance)){
        //     Transactions::createTransaction($cosdr);
        //     Transactions::createTransaction($inventory);
        // }

    }
}
