<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Supplier\Entities\Supplier;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
use App\User;
use Auth;

class Supplier extends Model
{
    protected $fillable = ['supplier_id','supplier_name','address','address2','mobile','email','city','state','country','details','status','sec_mobile'];

    public static function createSupplier($requestData){
        try{
            $id = static::create($requestData);
            return $id;

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }

    public static function updateSupplier($requestData, $supplier_id){
        try{
            $supplier = static::find($supplier_id);
            $supplier->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }

    public function accounts()
    {
        return $this->belongsTo(Accounts::class,'id','supplier_id');
    }
    public static function previous_balance_add($balance,$supplier_id)
    {
        $supplier = Supplier::find($supplier_id);
        $headn = $supplier_id.'-'.$supplier->supplier_name;
        $coainfo = Accounts::where('HeadName',$headn)->first();
        $supplier_headcode = $coainfo->HeadCode;
        $transaction_id = generateRandomStr(10);

        // Supplier debit for previous balance

        $cosdr = array(
            'VNo'            =>  $transaction_id,
            'Vtype'          =>  'PR Balance',
            'VDate'          =>  date("Y-m-d"),
            'COAID'          =>  $supplier_headcode,
            'Narration'      =>  'Supplier debit For '.$supplier->supplier_name,
            'Debit'          =>  0,
            'Credit'         =>  $balance,
            'IsPosted'       => 1,
            'created_by'       => Auth::user()->id,
            'updated_by'       => Auth::user()->id,
            'IsAppove'       => 1,
            'company_id'     => Auth::user()->company_id
            );
       $inventory = array(
            'VNo'            =>  $transaction_id,
            'Vtype'          =>  'PR Balance',
            'VDate'          =>  date("Y-m-d"),
            'COAID'          =>  10107,
            'Narration'      =>  'Inventory credit For Old Purchase For'.$supplier->supplier_name,
            'Debit'          =>  $balance,
            'Credit'         =>  0,//previous price asbe
            'IsPosted'       => 1,
            'created_by'       => Auth::user()->id,
            'updated_by'       => Auth::user()->id,
            'IsAppove'       => 1,
            'company_id'     => Auth::user()->company_id
            ); 

        if(!empty($balance)){
            Transactions::createTransaction($cosdr);
            Transactions::createTransaction($inventory);
        }

    }

}
