<?php

namespace Modules\Bank\Entities;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
class Bank extends Model
{
    protected $fillable = [
        'bank_id',
        'bank_name',
        'account_name',
        'account_no',
        'branch',
        'status',
        'sign',
        'company_id'
    ];

    // Create New Account
    public static function createBank($requestData){
        try{
            $id = static::create($requestData);
            return $id;
        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }

    public static function updateBank($requestData, $bank_id){
        try{
            $bank = static::find($bank_id);
            $bank->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }
    public function accounts()
    {
        return $this->belongsTo(Accounts::class,'id','bank_id');
    }
    public static function previous_balance_add($balance,$bank_id)
    {
        $bank = Bank::find($bank_id);
        $headn = $bank->bank_name.'-'.$bank->account_no;
        $coainfo = Accounts::where('HeadName',$headn)->first();
        $bank_headcode = $coainfo->HeadCode;
        $transaction_id = generateRandomStr(10);

        // Bank Opening debit for previous balance

        $cosdr = array(
            'VNo'            =>  $transaction_id,
            'Vtype'          =>  'Opening Balance',
            'VDate'          =>  date("Y-m-d"),
            'COAID'          =>  $bank_headcode,
            'Narration'      =>  'Opening Balance debit For '.$bank->bank_name,
            'Debit'          =>  $balance,
            'Credit'         =>  0,
            'IsPosted'       => 1,
            'created_by'       => Auth::user()->id,
            'updated_by'       => Auth::user()->id,
            'IsAppove'       => 1,
            'company_id'     => Auth::user()->company_id
            );

        if(!empty($balance)){
            Transactions::createTransaction($cosdr);
        }

    }
}
