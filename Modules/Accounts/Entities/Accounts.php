<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
use Modules\Customer\Entities\Customer;
use Modules\Bank\Entities\Bank;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Accounts extends Model
{
    protected $fillable = ['HeadCode','HeadName','PHeadName','PHeadCode','HeadLevel','IsActive','IsTransaction','IsGL','HeadType','IsBudget','IsDepreciation','customer_id','user_id','bank_id','company_id','DepreciationRate','created_by','updated_by','created_at','updated_at','supplier_id'];

    public static function createAccounts($requestData){
        try{
            $id = static::create($requestData);
            return $id;
        }catch(\Exception $e){

            throw new \Exception($e->getMessage(), 1);
        }
    }

    public function transactions()
    {
    	return $this->hasMany('\Modules\Accounts\Entities\Transactions','COAID','HeadCode');
    }
    public function child_codes()
    {
        return $this->hasMany('\Modules\Accounts\Entities\Accounts','PHeadCode','HeadCode');
    }
    public function childrenRecursive()
    {
        return $this->child_codes()->with('childrenRecursive');
    }

    public static function get_userlist()
    {
        $data = Accounts::where('IsActive',1)->orderBy('HeadCode')->get();
        return $data;
    }

    public static function dfs($HeadName,$HeadCode,$oResult,$visit,$d)
    {
        if($d==0) echo "<li class=\"jstree-open\">$HeadName";
        else if($d==1) echo "<li class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaData('".$HeadCode."')\">$HeadName</a>";
        else echo "<li><a href='javascript:' onclick=\"loadCoaData('".$HeadCode."')\">$HeadName</a>";
        $p=0;
        for($i=0;$i< count($oResult);$i++)
        {
            if (!$visit[$i])
            {
                if ($HeadCode==$oResult[$i]->PHeadCode)
                {
                    $visit[$i]=true;
                    if($p==0) echo "<ul>";
                    $p++;
                    Accounts::dfs($oResult[$i]->HeadName,$oResult[$i]->HeadCode,$oResult,$visit,$d+1);
                }
            }
        }
        if($p==0)
            echo "</li>";
        else
            echo "</ul>";
    }

    public static function treeview_selectform($id){
        $data = Accounts::where('HeadCode',$id)->get();
        return $data;
    }

    public static function customer_receive_insert($data)
    {
        $voucher_no = $data['VNo'];
        $Vtype      = "CR";
        $dAID            = $data['txtCode'];
        $Debit           = 0;
        $Credit          = $data['txtAmount'];
        $VDate           = $data['VDate'];
        $customer_id     = $data['customer_id'];
        $Narration       = $data['remark'];
        $IsPosted=1;
        $IsAppove=0;
        $created_by      = $data['created_by'];
        $updated_by      = $data['updated_by'];
        $dbtid           = $dAID;
        $Credit          = $Credit;
        $customerid      = $customer_id;
        $customerinfo = Customer::find($customerid);
        $customercredit = array(
            'VNo'            =>  $voucher_no,
            'Vtype'          =>  $Vtype,
            'VDate'          =>  $VDate,
            'COAID'          =>  $dbtid,
            'Narration'      =>  $Narration,
            'Debit'          =>  0,
            'Credit'         =>  $Credit,
            'IsPosted'       =>  $IsPosted,
            'created_by'     =>  $created_by,
            'updated_by'     =>  $updated_by,
            'company_id'     =>  $data['company_id'],
            'IsAppove'       =>  $IsAppove
        );

        $cc = array(
            'VNo'            =>  $voucher_no,
            'Vtype'          =>  $Vtype,
            'VDate'          =>  $VDate,
            'COAID'          =>  1020301,
            'Narration'      =>  'Cash in Hand For  '.$customerinfo->customer_name,
            'Debit'          =>  $Credit,
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'created_by'     =>  $created_by,
            'updated_by'     =>  $updated_by,
            'company_id'     =>  $data['company_id'],
            'IsAppove'       =>  $IsAppove
        );
        $bankc = array(
            'VNo'            =>  $voucher_no,
            'Vtype'          =>  $Vtype,
            'VDate'          =>  $VDate,
            'COAID'          =>  $data['bank_coaid'],
            'Narration'      =>  'Customer Receive From '.$customerinfo->customer_name,
            'Debit'          =>  $Credit,
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'created_by'     =>  $created_by,
            'updated_by'     =>  $updated_by,
            'company_id'     =>  $data['company_id'],
            'IsAppove'       =>  $IsAppove
        );
        $chk = array(
            'VNo'            =>  $voucher_no,
            'Vtype'          =>  $Vtype,
            'VDate'          =>  $VDate,
            'COAID'          =>  $data['chk_head'],
            'Narration'      =>  'Check Receive From '.$customerinfo->customer_name,
            'Debit'          =>  $Credit,
            'Credit'         =>  0,
            'IsPosted'       =>  1,
            'created_by'     =>  $created_by,
            'updated_by'     =>  $updated_by,
            'company_id'     =>  $data['company_id'],
            'IsAppove'       =>  $IsAppove
        );

        Transactions:: createTransaction($customercredit);

        if($data['paytype'] == 2){
            Transactions::createTransaction($bankc);
        }
        if($data['paytype'] == 1){
            Transactions::createTransaction($cc);
        }
        if($data['paytype'] == 3){
            Transactions::createTransaction($chk);
        }
    }

    public static function get_new_code($n)
    {
        $code = Accounts::where('HeadCode',$n)->first();
        if ($code!== null) {
            $ne_code = $n + 1;
            $x = Accounts::get_new_code($ne_code);
            return $x;
        }else{
            return $n;
        }
    }


    // Function for Balance Sheet
    public static function get_trial_balance($headcode){
            $code = $headcode;
            $balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))->where('COAID','LIKE',$code.'%')->get();
            return $balance;
    }

    public static function get_trial_balance_by_date($fromDate, $toDate, $headcode){
        $code = $headcode;
        $balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
                                ->where('COAID','LIKE',$code.'%')
                                ->whereBetween('VDate',[$fromDate, $toDate])
                                ->get();
        return $balance;
    }

    // Balance Sheet Function End

    public static function get_trial_balance_brought_forward($headcode){
        $code = $headcode;
        $lastDay = Carbon::yesterday()->toDateString();
        $brought_forward_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
                                ->where('COAID','LIKE',$code.'%')
                                ->where('VDate','<', $lastDay)
                                ->get();
        return $brought_forward_balance;
    }

    public static function get_trial_balance_this_period($headcode){
        $code = $headcode;
        $firstDay = Carbon::today()->startOfMonth()->toDateString();
        $lastDay = Carbon::today()->endOfMonth()->toDateString();
        $this_period_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
                                ->where('COAID','LIKE',$code.'%')
                                ->whereBetween('VDate',[$firstDay, $lastDay])
                                ->get();
        return $this_period_balance;
    }

//    Searching By Date

    public static function get_trial_balance_brought_forward_by_date($headcode, $from, $to){
        $code = $headcode;
        $lastDay = Carbon::parse($from)->subDay(1)->toDateString();
        $brought_forward_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
            ->where('COAID','LIKE',$code.'%')
            ->where('VDate','<', $lastDay)
            ->get();
        return $brought_forward_balance;
    }

    public static function get_trial_balance_this_period_by_date($headcode, $from, $to){
        $code = $headcode;
        $this_period_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
            ->where('COAID','LIKE',$code.'%')
            ->whereBetween('VDate',[$from, $to])
            ->get();
        return $this_period_balance;
    }

}
