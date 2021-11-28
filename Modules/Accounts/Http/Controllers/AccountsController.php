<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\CheckList;
use Modules\Accounts\Entities\Transactions;
use Modules\Company\Entities\Company;
use Modules\Customer\Entities\Customer;
use Modules\Bank\Entities\Bank;
use PDF;
use Auth;
use DB;
use DataTables;
use App\User;
use Carbon\Carbon;

class AccountsController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $userList = Accounts::get_userlist();
        return view('accounts::index',compact('userList'));
    }

    public function get_code(Request $request,$id)
    {
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                $coa_info = Accounts::where('HeadCode',$id)->first();
                if ($request->PHead !== $coa_info->PHeadCode) {
                    $name = Accounts::select('HeadName')->where('HeadCode',$request->PHead)->first();
                    Accounts::where('HeadCode',$id)->update(['PHeadName'=>$name->HeadName,'PHeadCode'=>$request->PHead]);
                }
                // Is GL
                if ($request->IsGL) {
                    Accounts::where('HeadCode',$id)->update(['IsGL'=>$data['IsGL']]);
                }else{
                    Accounts::where('HeadCode',$id)->update(['IsGL'=>0]);
                }

                // Is Transection
                if ($request->IsTransaction) {
                    Accounts::where('HeadCode',$id)->update(['IsTransaction'=>$data['IsTransaction']]);
                }else{
                    Accounts::where('HeadCode',$id)->update(['IsTransaction'=>0]);
                }
                // IsActive
                if ($request->IsActive) {
                    Accounts::where('HeadCode',$id)->update(['IsActive'=>$data['IsActive']]);
                }else{
                    Accounts::where('HeadCode',$id)->update(['IsActive'=>0]);
                }

                Accounts::where('HeadCode',$id)->update(['HeadName'=> $data['HeadName']]);
                Accounts::where('PHeadName',$data['PrevHeadName'])->update(['PHeadName'=> $data['HeadName']]);
                $prb = Transactions::where('COAID',$id)->where('Vtype','PR Balance')->orwhere('Vtype','Opening Balance')->where('COAID',$id)->first();
                // return $prb;

                if ($prb) {
                    if (( !$prb && $request->opb_credit > 0) || (!$prb && $request->opb_debit > 0) ) {
                        $opb = array(
                            'VNo'            =>  'OB',
                            'Vtype'          =>  'Opening Balance',
                            'VDate'          =>  date('Y-m-d'),
                            'COAID'          =>  $id,
                            'Narration'      =>  'Opening Balance for '.$data['HeadName'],
                            'Debit'          =>  $request->opb_debit,
                            'Credit'         =>  $request->opb_credit,
                            'IsPosted'       =>  1,
                            'created_by'     =>  $this->user->id,
                            'updated_by'     =>  $this->user->id,
                            'company_id'     =>  $this->user->company_id,
                            'IsAppove'       =>  1
                        );
                        Transactions::createTransaction($opb);
                        return response()->json(['status'=>'success'], 200);

                    }else{
                        Transactions::where('id',$prb->id)->update(['Credit'=>$request->opb_credit,'Debit'=>$request->opb_debit]);
                    return response()->json(['status'=>'success'], 200);

                    }
                }else{
                    $opb = array(
                            'VNo'            =>  'OB',
                            'Vtype'          =>  'Opening Balance',
                            'VDate'          =>  date('Y-m-d'),
                            'COAID'          =>  $id,
                            'Narration'      =>  'Opening Balance for '.$data['HeadName'],
                            'Debit'          =>  $request->opb_debit,
                            'Credit'         =>  $request->opb_credit,
                            'IsPosted'       =>  1,
                            'created_by'     =>  $this->user->id,
                            'updated_by'     =>  $this->user->id,
                            'company_id'     =>  $this->user->company_id,
                            'IsAppove'       =>  1
                        );
                    Transactions::createTransaction($opb);
                    return response()->json(['status'=>'success'], 200);
                }
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $data = Accounts::where('HeadCode',$id)->first();
            $hlevel = $data->HeadLevel;
            $level = (int)$hlevel;
            if ($level>1) {
                $phead = Accounts::where('HeadCode',$data->PHeadCode)->first();
                $other_heads = Accounts::select('HeadCode','HeadName')->where('PHeadCode',$phead->PHeadCode)->get();
            }else{
                $other_heads[] = (object)['HeadName'=>$data->PHeadName,'HeadCode'=>$data->PHeadCode];
            }
            if ($data->IsTransaction == 1) {
                $prb = Transactions::where('COAID',$id)->where('Vtype','PR Balance')->orwhere('Vtype','Opening Balance')->where('COAID',$id)->first();

                if ($prb == null) {
                    $prb_cr = 0;
                    $prb_deb = 0;
                }else{
                    $prb_cr = $prb->Credit;
                    $prb_deb = $prb->Debit;
                }
            }else{
                $prb_deb =0;
                $prb_cr =0;
            }
            return view('accounts::code_view',compact('data','prb_cr','prb_deb','other_heads'));
        }
    }

    public function new_code(Request $request,$id)
    {
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                if (!$request->IsGL) {
                    $data['IsGL'] = 0;
                }
                if (!$request->IsBudget) {
                    $data['IsBudget'] = 0;
                }
                if (!$request->IsTransaction) {
                    $data['IsTransaction'] = 0;
                }
                if (!$request->IsActive) {
                    $data['IsActive'] = 0;
                }
                if (!$request->IsDepreciation) {
                    $data['IsDepreciation'] = 0;
                }
                if (!$request->DepreciationRate) {
                    $data['DepreciationRate'] = 0;
                }
                $data['created_by'] = $this->user->id;
                Accounts::createAccounts($data);
                return response()->json(['status'=>'success','data'=>$data], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $data = Accounts::where('HeadCode',$id)->first();
            $new_id = Accounts::where('PHeadCode',$data->HeadCode)->count();
            $n =$new_id + 1;
            if ($n / 10 < 1){
                $HeadCode = $id . "0" . $n;
            }else{
                $HeadCode = $id . $n;
            }
            $new_headcode = Accounts::get_new_code($HeadCode);
            $info['HeadCode'] =  $new_headcode;
            $info['HeadLevel'] =  $data->HeadLevel+1;
            $info['PHeadName'] = $data->HeadName;
            $info['PHeadCode'] = $data->HeadCode;
            $info['HeadType'] = $data->HeadType;
            return view('accounts::new_code',compact('info'));
        }
    }

    public function customer_receive(Request $request)
    {
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                $transaction = Transactions::where('VNo','like','CR-'.'%')->latest()->first();
                if ($transaction == null) {
                    $cr = 'CR-1';
                }else{
                    $v_n = $transaction->VNo;
                    $v_id = substr($v_n, strpos($v_n, "-") + 1);
                    $v_id_int = (int)$v_id + 1;
                    $cr = 'CR-'.$v_id_int;
                }
                $data['VNo'] = $cr;
                $coa_id = Accounts::select('HeadCode')->where('customer_id',$data['customer_id'])->first();
                if ($request->paytype == 3) {

                    $coa = Accounts::where('HeadLevel',3)->where('HeadCode','Like','1020600'.'%')->latest()->first();
                    if ($coa!=NULL) {
                        $num = $coa['HeadCode'];
                        $int = (int)$num;
                        $headcode=$int+1;
                    }
                    else{
                        $headcode="102060000001";
                    }
                    $c_acc='CHK-'.$data['check_no'];
                    $created_by=$this->user->id;
                    $customer_coa['HeadCode'] = $headcode;
                    $customer_coa['HeadName'] = $c_acc;
                    $customer_coa['PHeadName'] = 'Cheques In Hand';
                    $customer_coa['PHeadCode'] = '10206';
                    $customer_coa['HeadLevel'] = '3';
                    $customer_coa['IsActive'] = '1';
                    $customer_coa['IsTransaction'] = '1';
                    $customer_coa['IsGL'] = '0';
                    $customer_coa['HeadType'] = 'A';
                    $customer_coa['IsBudget'] = '0';
                    $customer_coa['IsDepreciation'] = '0';
                    $customer_coa['DepreciationRate'] = '0';
                    $customer_coa['created_by'] = $this->user->id;
                    Accounts::createAccounts($customer_coa);
                    $data['chk_head'] = $headcode;
                    $n_data['check_no'] = $data['check_no'];
                    $n_data['VDate'] = $data['VDate'];
                    $n_data['mat_date'] = $data['mat_date'];
                    $n_data['COAID'] = $headcode;
                    $n_data['customer_id'] = $data['customer_id'];
                    $n_data['company_id'] = $data['company_id'];
                    $n_data['bank_name'] = $data['bank_name'];
                    $n_data['is_credited'] = 0;
                    CheckList::add_check($n_data);
                }else{
                   $data['chk_head']= '';
                }

                if ($request->bank_id) {
                    $bank = Bank::where('bank_id',$data['bank_id'])->first();
                    $b_name = $bank->bank_name;
                    $c_id = Auth::user()->company_id;
                    $h_name = $b_name.'-'.$bank->account_no;
                    $acc_info = Accounts::where('HeadName',$h_name)->first();
                    $head_code = $acc_info->HeadCode;
                    $data['bank_coaid'] = $head_code;
                }else{
                    $head_code = '';
                    $data['bank_coaid']= $head_code;
                }
                $data['created_by'] = $this->user->id;
                $data['updated_by'] = $this->user->id;
                $data['company_id'] = $data['company_id'];
                Accounts::customer_receive_insert($data);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $customers = Customer::get();
            $companies = Company::where('parent_id','!=',0)->get();
            return view('accounts::customer_receive',compact('customers','companies'));
        }
    }
    public function get_bank()
    {
        $data = Bank::get();
        return $data;
    }
    public function get_accode($id)
    {
        $customer = Customer::find($id);
        $head_name =$id.'-'.$customer->customer_name;
        $customerhcode = Accounts::where('HeadName',$head_name)->first();
        $code = $customerhcode->HeadCode;
        return $code;
    }

    public function debit_voucher(Request $request){
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                /*Insert Debit data for Debit Voucher*/
                foreach ($data['HeadCode'] as $key => $value) {
                    $n_data['COAID'] = $data['HeadCode'][$key];
                    $n_data['Debit'] = $data['txtAmount'][$key];
                    $n_data['Credit'] = 0;
                    $n_data['IsPosted'] = 1;
                    $n_data['VNo'] = $data['VNo'];
                    $n_data['Vtype'] = 'DV';
                    $n_data['VDate'] = $data['VDate'];
                    $n_data['Narration'] = $data['remark'];
                    $n_data['company_id'] = $this->user->company_id;
                    $n_data['created_by'] = $this->user->id;
                    $n_data['updated_by'] = $this->user->id;
                    $n_data['IsAppove'] = 0;
                    Transactions::createTransaction($n_data);

                    /*Insert Credit data for Debit Voucher*/
                    $headinfo = Accounts::select('HeadName')->where('HeadCode',$data['payment_from'])->first();
                    $c_data['COAID'] = $data['payment_from'];
                    $c_data['Debit'] = 0;
                    $c_data['Credit'] = $data['txtAmount'][$key];
                    $c_data['IsPosted'] = 1;
                    $c_data['VNo'] = $data['VNo'];
                    $c_data['Vtype'] = 'DV';
                    $c_data['VDate'] = $data['VDate'];
                    $c_data['Narration'] = 'Debit voucher from '.$headinfo->HeadName;
                    $c_data['company_id'] = $this->user->company_id;
                    $c_data['created_by'] = $this->user->id;
                    $c_data['updated_by'] = $this->user->id;
                    $c_data['IsAppove'] = 0;

                    Transactions::createTransaction($c_data);
                }

                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $cr_heads = Accounts::where('HeadCode','like','102030'.'%')->orwhere('HeadCode','like','10202'.'%')->where('IsTransaction',1)->where('IsActive',1)->get();
            $trans = Accounts::where('IsTransaction',1)->where('IsActive',1)->get();
            $v_no = Transactions::select('VNo')->where('VNo','like','DV-'.'%')->latest()->first();
            if ($v_no == null) {
                $v_no = 'DV-1';
            }else{
                $v_n = $v_no->VNo;
                $v_id = substr($v_n, strpos($v_n, "-") + 1);
                $v_id_int = (int)$v_id + 1;
                $v_no = 'DV-'.$v_id_int;
            }
            return view('accounts::debit_voucher',compact('v_no','trans','cr_heads'));
        }
    }
    public function credit_voucher(Request $request){
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                /*Insert Credit data for Credit Voucher*/
                $headinfo = Accounts::select('HeadName')->where('HeadCode',$data['payment_from'])->first();
                foreach ($data['HeadCode'] as $key => $value) {
                    $n_data['COAID'] = $data['HeadCode'][$key];
                    $n_data['Debit'] = 0;
                    $n_data['Credit'] = $data['txtAmount'][$key];
                    $n_data['IsPosted'] = 1;
                    $n_data['VNo'] = $data['VNo'];
                    $n_data['Vtype'] = 'CRV';
                    $n_data['VDate'] = $data['VDate'];
                    $n_data['Narration'] = $data['remark'].'-from '.$headinfo->HeadName;
                    $n_data['company_id'] = $this->user->company_id;
                    $n_data['created_by'] = $this->user->id;
                    $n_data['updated_by'] = $this->user->id;
                    $n_data['IsAppove'] = 0;
                    Transactions::createTransaction($n_data);
                    /*Insert Debit data for Credit Voucher*/
                    $c_data['COAID'] = $data['payment_from'];
                    $c_data['Debit'] = $data['txtAmount'][$key];
                    $c_data['Credit'] = 0;
                    $c_data['IsPosted'] = 1;
                    $c_data['VNo'] = $data['VNo'];
                    $c_data['Vtype'] = 'CRV';
                    $c_data['VDate'] = $data['VDate'];
                    $c_data['Narration'] =  $data['remark'];
                    $c_data['company_id'] = $this->user->company_id;
                    $c_data['created_by'] = $this->user->id;
                    $c_data['updated_by'] = $this->user->id;
                    $c_data['IsAppove'] = 0;

                    Transactions::createTransaction($c_data);
                }

                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $cr_heads = Accounts::where('HeadCode','like','10201'.'%')->where('IsTransaction',1)->where('IsTransaction',1)->orderBy('HeadName')->get();
            $trans = Accounts::where('HeadCode','like','102030'.'%')->orwhere('HeadCode','like','10202'.'%')->where('IsTransaction',1)->where('IsActive',1)->get();
            $v_no = Transactions::select('VNo')->where('VNo','like','CRV-'.'%')->latest()->first();
            if ($v_no == null) {
                $v_no = 'CRV-1';
            }else{
                $v_n = $v_no->VNo;
                $v_id = substr($v_n, strpos($v_n, "-") + 1);
                $v_id_int = (int)$v_id + 1;
                $v_no = 'CRV-'.$v_id_int;
            }
            return view('accounts::credit_voucher',compact('v_no','trans','cr_heads'));
        }
    }

    public function contra_voucher(Request $request)
    {
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                /*Insert Credit data for Contra Voucher*/
                foreach ($data['HeadCode'] as $key => $value) {
                    $headinfo = Accounts::select('HeadName')->where('HeadCode',$data['payment_from'])->first();
                    /*Insert Credit data for Contra Voucher*/
                    $n_data['COAID'] = $data['HeadCode'][$key];
                    $n_data['Debit'] = 0;
                    $n_data['Credit'] = $data['txtAmount'][$key];
                    $n_data['IsPosted'] = 1;
                    $n_data['VNo'] = $data['VNo'];
                    $n_data['Vtype'] = 'COV';
                    $n_data['VDate'] = $data['VDate'];
                    $n_data['Narration'] = $data['remark'].' - At - '.$headinfo->HeadName;
                    $n_data['company_id'] = $this->user->company_id;
                    $n_data['created_by'] = $this->user->id;
                    $n_data['updated_by'] = $this->user->id;
                    $n_data['IsAppove'] = 0;
                    Transactions::createTransaction($n_data);
                    /*Insert Debit data for Contra Voucher*/
                    $c_data['COAID'] = $data['payment_from'];
                    $c_data['Debit'] = $data['txtAmount'][$key];
                    $c_data['Credit'] = 0;
                    $c_data['IsPosted'] = 1;
                    $c_data['VNo'] = $data['VNo'];
                    $c_data['Vtype'] = 'COV';
                    $c_data['VDate'] = $data['VDate'];
                    $c_data['Narration'] =  $data['remark'].' - At - '.$headinfo->HeadName;
                    $c_data['company_id'] = $this->user->company_id;
                    $c_data['created_by'] = $this->user->id;
                    $c_data['updated_by'] = $this->user->id;
                    $c_data['IsAppove'] = 0;
                    Transactions::createTransaction($c_data);
                }

                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $trans = Accounts::where('HeadCode','like','102020'.'%')->orwhere('HeadCode','like','102030'.'%')->where('IsActive',1)->get();
            $v_no = Transactions::select('VNo')->where('VNo','like','COV-'.'%')->latest()->first();
            if ($v_no == null) {
                $v_no = 'COV-1';
            }else{
                $v_n = $v_no->VNo;
                $v_id = substr($v_n, strpos($v_n, "-") + 1);
                $v_id_int = (int)$v_id + 1;
                $v_no = 'COV-'.$v_id_int;
            }

            return view('accounts::contra_voucher',compact('v_no','trans'));
        }
    }
    public function journal_voucher(Request $request)
    {
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                /*Insert Credit and Debit data for Journal Voucher*/
                foreach ($data['HeadCode'] as $key => $value) {
                    $n_data['COAID'] = $data['HeadCode'][$key];
                    $n_data['Debit'] = $data['debtAmount'][$key];
                    $n_data['Credit'] = $data['creAmount'][$key];
                    $n_data['IsPosted'] = 1;
                    $n_data['VNo'] = $data['VNo'];
                    $n_data['Vtype'] = 'JV';
                    $n_data['VDate'] = $data['VDate'];
                    $n_data['Narration'] = $data['remark'];
                    $n_data['company_id'] = $this->user->company_id;
                    $n_data['created_by'] = $this->user->id;
                    $n_data['updated_by'] = $this->user->id;
                    $n_data['IsAppove'] = 0;
                    $n_data['Narration'] = $data['remark'];
                    Transactions::createTransaction($n_data);
                }
                return response()->json(['status'=>'success'], 200);
                }catch(\Exception $e){
                    return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $trans = Accounts::where('IsTransaction',1)->where('IsActive',1)->get();
            $v_no = Transactions::select('VNo')->where('VNo','like','JV-'.'%')->latest()->first();
            if ($v_no == null) {
                $v_no = 'JV-1';
            }else{
                $v_n = $v_no->VNo;
                $v_id = substr($v_n, strpos($v_n, "-") + 1);
                $v_id_int = (int)$v_id + 1;
                $v_no = 'JV-'.$v_id_int;
            }
            return view('accounts::journal_voucher',compact('v_no','trans'));
        }
    }
    public function voucher_list()
    {
        $vouchers = Transactions::select(DB::raw('`VNo`,`Vtype`,`VDate`,`created_by`,IsAppove, SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))->whereIn('Vtype', array('DV', 'CRV','CR','JV','COV'))->groupBy('VNo','Vtype','VDate','IsAppove','created_by')->get();
        foreach ($vouchers as $key => $value) {
            $narration = Transactions::select('Narration')->where('VNo',$vouchers[$key]->VNo)->first();
            $vouchers[$key]['remark'] = $narration->Narration;
        }
        return view('accounts::voucher_list',compact('vouchers'));
    }
    public function voucher_approve()
    {
        $vouchers = Transactions::select(DB::raw('`VNo`,`Vtype`,`VDate`,`created_by`, SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))->whereIn('Vtype', array('DV', 'CRV','CR', 'JV','COV'))->where('IsAppove',0)->groupBy('VNo','Vtype','VDate','created_by')->get();
        foreach ($vouchers as $key => $value) {
            $narration = Transactions::select('Narration')->where('VNo',$vouchers[$key]->VNo)->first();
            $vouchers[$key]['remark'] = $narration->Narration;
        }
        return view('accounts::voucher_approve',compact('vouchers'));
    }

    public function approve_voucher($v_id)
    {
        try{
            Transactions::where('VNo',$v_id)->update(['IsAppove'=>1]);
            return response()->json(['status'=>'success'], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }
    public function print_voucher($v_id)
    {
        $pdf_style = '<style>
            *{
                font-size:15px;
            }
            table,td, th {
                border: 1px solid black;
                border-collapse: collapse;
                width:100%;
            }
        </style>';
        $v_details = Transactions::select(DB::raw('`VNo`,`VDate`,SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))->where('VNo',$v_id)->groupBy('VNo','VDate')->get();
        $t_amount = (int)$v_details[0]->Debit;
        $amount = convert_number_to_words($t_amount);
        $narration = Transactions::select('Narration')->where('VNo',$v_id)->first();
        $vouchers = Transactions::with('coa')->where('VNo',$v_id)->get();
        if (strpos($v_id,'DV-') !== false) {
            $title = 'Debit Voucher';
        }elseif (strpos($v_id,'CRV-') !== false) {
            $title = 'Credit Voucher';
        }elseif (strpos($v_id,'JV-') !== false) {
            $title = 'Journal Voucher';
        }else{
            $title = 'Contra Voucher';
        }
        $company_info = Company::where('id',$this->user->company_id)->first();
        $pdf = PDF::loadView('accounts::voucher_print',compact('vouchers','company_info','pdf_style','title','v_details','narration','amount'));
        $pdf->setPaper('A4', 'potrait');
        $name = $v_id.".pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }

    // Print Cashbook
    public function print_cashbook($v_id)
    {
        $pdf_style = '<style>
            *{
                font-size:15px;
            }
            table,td, th {
                border: 1px solid black;
                border-collapse: collapse;
                width:100%;
            }
        </style>';
        $v_details = Transactions::select(DB::raw('`VNo`,`VDate`,SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))->where('VNo',$v_id)->groupBy('VNo','VDate')->get();
        $t_amount = (int)$v_details[0]->Debit;
        $amount = convert_number_to_words($t_amount);
        $narration = Transactions::select('Narration')->where('VNo',$v_id)->first();
        $vouchers = Transactions::with('coa')->where('VNo',$v_id)->get();
        $cashbook = 'Cashbook of , ';
        if (strpos($v_id,'DV-') !== false) {
            $title = $cashbook.'Debit Voucher';
        }elseif (strpos($v_id,'CRV-') !== false) {
            $title = $cashbook.'Credit Voucher';
        }elseif (strpos($v_id,'JV-') !== false) {
            $title = $cashbook.'Journal Voucher';
        }else{
            $title = $cashbook.'Contra Voucher';
        }
        $company_info = Company::where('id',$this->user->company_id)->first();
        $pdf = PDF::loadView('accounts::cashbook_print',compact('vouchers','company_info','pdf_style','title','v_details','narration','amount'));
        $pdf->setPaper('A4', 'potrait');
        $name = $v_id.".pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }


    public function checks_ih(Request $request)
    {
        $checks = CheckList::with('customer')->where('is_credited',0)->orwhere('is_credited',2)->get();
        $tdate = date('Y-m-d');
        return view('accounts::check_list',compact('checks','tdate'));
    }

    public function check_update(Request $request,$check_id,$status)
    {
        $status = $status;
        $check_id = $check_id;
        try{
            CheckList::where('id',$check_id)->update(['is_credited'=>1]);
            return response()->json(['status'=>'success'], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }
    public function cr_check(Request $request,$check_id= null)
    {
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                /*Insert Credit data for Credit Voucher*/
                $headinfo = Accounts::select('HeadName')->where('HeadCode',$data['payment_from'])->first();
                foreach ($data['HeadCode'] as $key => $value) {
                    $n_data['COAID'] = $data['payment_from'];
                    $n_data['Debit'] = $data['txtAmount'][$key];
                    $n_data['Credit'] = 0;
                    $n_data['IsPosted'] = 1;
                    $n_data['VNo'] = $data['VNo'];
                    $n_data['Vtype'] = 'CRV';
                    $n_data['VDate'] = $data['VDate'];
                    $n_data['Narration'] = $data['remark'].'-from '.$headinfo->HeadName;
                    $n_data['company_id'] = $this->user->company_id;
                    $n_data['created_by'] = $this->user->id;
                    $n_data['updated_by'] = $this->user->id;
                    $n_data['IsAppove'] = 0;
                    Transactions::createTransaction($n_data);

                    /*Insert Debit data for Credit Voucher*/
                    $c_data['COAID'] = $data['HeadCode'][$key];
                    $c_data['Debit'] = 0;
                    $c_data['Credit'] = $data['txtAmount'][$key];
                    $c_data['IsPosted'] = 1;
                    $c_data['VNo'] = $data['VNo'];
                    $c_data['Vtype'] = 'CRV';
                    $c_data['VDate'] = $data['VDate'];
                    $c_data['Narration'] =  $data['remark'];
                    $c_data['company_id'] = $this->user->company_id;
                    $c_data['created_by'] = $this->user->id;
                    $c_data['updated_by'] = $this->user->id;
                    $c_data['IsAppove'] = 0;
                    Transactions::createTransaction($c_data);
                }

                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $cr_heads = Accounts::where('HeadCode','like','102030'.'%')->orwhere('HeadCode','like','102020'.'%')->get();
            $v_no = Transactions::select('VNo')->where('VNo','like','CRV-'.'%')->latest()->first();
            if ($v_no == null) {
                $v_no = 'CRV-1';
            }else{
                $v_n = $v_no->VNo;
                $v_id = substr($v_n, strpos($v_n, "-") + 1);
                $v_id_int = (int)$v_id + 1;
                $v_no = 'CRV-'.$v_id_int;
            }
            $check = CheckList::where('id',$check_id)->first();
            $amount = Transactions::select('Debit')->where('COAID',$check->COAID)->first();
            $chk_amount = $amount->Debit;
            return view('accounts::contra_check',compact('v_no','check','chk_amount','cr_heads'));
        }
    }
    public function trial_balance(Request $request)
    {
        $accounts = Accounts::with('childrenRecursive')->where('IsActive',1)->where('PHeadCode',0)->get();

        $grand_total_brought_forward_debit = 0;
		$grand_total_brought_forward_credit = 0;

		$grand_total_this_period_debit = 0;
		$grand_total_this_period_credit = 0;

        foreach( $accounts as $account ){
            foreach( $account->childrenRecursive as $child ){
                $code = $child->HeadCode;

                //brought forward grand total start
                $lastDay = Carbon::yesterday()->toDateString();
                $brought_forward_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
                                ->where('COAID','LIKE',$code.'%')
                                ->where('VDate','<', $lastDay)
                                ->get();
                $grand_total_brought_forward_debit += $brought_forward_balance[0]->Debit;
                $grand_total_brought_forward_credit += $brought_forward_balance[0]->Credit;

                //this period grand total start
                $firstDay = Carbon::today()->startOfMonth()->toDateString();
                $lastDay = Carbon::today()->endOfMonth()->toDateString();
                $this_period_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
                                        ->where('COAID','LIKE',$code.'%')
                                        ->whereBetween('VDate',[$firstDay, $lastDay])
                                        ->get();
                $grand_total_this_period_debit += $this_period_balance[0]->Debit;
                $grand_total_this_period_credit += $this_period_balance[0]->Credit;
            }
        }

        $edate = date('Y-m-d');
        $sdate = date('Y-m-d');
        $company_info = Company::where('id',$this->user->company_id)->first();
        return view('accounts::trial_balance',compact(
            'company_info','edate','sdate','accounts','grand_total_brought_forward_debit','grand_total_brought_forward_credit',
            'grand_total_this_period_debit','grand_total_this_period_credit'
        ));
    }

    // Trail Balance By Date
    public function trial_balance_by_date(Request $request){
        $from = $request->from;
        $to = $request->to;

        $previousDay = Carbon::parse($from)->subDay(1)->toDateString();

        $accounts = Accounts::with('childrenRecursive')->where('IsActive',1)->where('PHeadCode',0)->get();

        $grand_total_brought_forward_debit = 0;
        $grand_total_brought_forward_credit = 0;

        $grand_total_this_period_debit = 0;
        $grand_total_this_period_credit = 0;

        foreach( $accounts as $account ){
            foreach( $account->childrenRecursive as $child ){
                $code = $child->HeadCode;

                //brought forward grand total start
                $lastDay = Carbon::yesterday()->toDateString();
                $brought_forward_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
                    ->where('COAID','LIKE',$code.'%')
                    ->where('VDate','<', $previousDay)
                    ->get();
                $grand_total_brought_forward_debit += $brought_forward_balance[0]->Debit;
                $grand_total_brought_forward_credit += $brought_forward_balance[0]->Credit;

                //this period grand total start
                $this_period_balance = Transactions::select(DB::raw('SUM(`Debit`) as Debit,SUM(`Credit`) as Credit'))
                    ->where('COAID','LIKE',$code.'%')
                    ->whereBetween('VDate',[$from, $to])
                    ->get();
                $grand_total_this_period_debit += $this_period_balance[0]->Debit;
                $grand_total_this_period_credit += $this_period_balance[0]->Credit;
            }
        }

        $edate = date('Y-m-d');
        $sdate = date('Y-m-d');
        $company_info = Company::where('id',$this->user->company_id)->first();
        return view('accounts::trial_balance_search_by_date',compact(
            'company_info','edate','sdate','accounts','grand_total_brought_forward_debit','grand_total_brought_forward_credit',
            'grand_total_this_period_debit','grand_total_this_period_credit','from','to'
        ));
    }
    public function balance_sheet(Request $request)
    {
        $accounts = Accounts::with('childrenRecursive')->where('IsActive',1)->where('PHeadCode',0)->get();
        //dd($accounts);exit();
        $edate = date('Y-m-d');
        $sdate = date('Y-m-d');
        $company_info = Company::where('id',$this->user->company_id)->first();
        return view('accounts::balance_sheet',compact('company_info','edate','sdate','accounts'));
    }

    public function balance_sheet_by_date(Request $request){
        if($request->ajax()){
            $fromDate = $request->from;
            $toDate = $request->to;
            $accounts = Accounts::with('childrenRecursive')
                                ->where('IsActive',1)
                                ->where('PHeadCode',0)
                                ->get();
            $company_info = Company::where('id',$this->user->company_id)->first();

            // End
            return response()->json(['company_info' => $company_info, 'accounts' => $accounts,'fromSearchDate'=>$fromDate, 'toSearchDate'=>$toDate, 'balance' => $balance], 200);
        }
    }

    //search balance
    public function balance_search(Request $request){
        if($request){
            $fromDate = $request->from;
            $toDate = $request->to;
            $accounts = Accounts::with('childrenRecursive')
                                ->where('IsActive',1)
                                ->where('PHeadCode',0)
                                ->get();
            $company_info = Company::where('id',$this->user->company_id)->first();

            // End
            return view('accounts::balance_sheet_by_date',compact('company_info','accounts','fromDate','toDate'));
        }
    }

    public function inventory_ledger()
    {
        // return $inventory_type;
        $transactions = Transactions::where('COAID','10301')->get();
        $balance = [];
        $total_debit = Transactions::where('COAID','10301')
                                            ->sum('Debit');
        $total_credit = Transactions::where('COAID','10301')
                                            ->sum('Credit');

        $total = 0;
        foreach ($transactions as $key => $cash) {
            if( $cash->Vtype == "Opening Balance" && $cash->Credit == 0){
                array_push($balance, $cash->Debit);
            }
            elseif($cash->Vtype == "Opening Balance" && $cash->Debit == 0){
                array_push($balance, $cash->Credit);
            }
            else{
                if( count($balance) > 0 ){
                    if($cash->Credit == 0){
                    array_push($balance, $balance[count($balance) - 1] + $cash->Debit);
                    }elseif($cash->Debit == 0){
                        array_push($balance, $balance[count($balance) - 1] - $cash->Credit);
                    }
                }else{
                    if($cash->Credit == 0){
                        array_push($balance,$cash->Debit);
                    }elseif($cash->Debit == 0){
                        array_push($balance,$cash->Credit);
                    }
                }

            }
            $total = $balance[$key];

        }
        $company_info = Company::where('id',$this->user->company_id)->first();
        // return $transactions;
        return view('accounts::InventoryLadger.inventory_ladger', compact('transactions','company_info','balance','total_debit','total_credit','total'));
    }

    //inventory ledger by date
    public function inventory_ledger_by_date(Request $request){
        if( $request->ajax() ){
            $from = $request->get('from');
            $to = $request->get('to');
            $transactions = Transactions::where('COAID','10301')
                                        ->whereBetween('VDate',[$from, $to])
                                        ->get();
            $balance = [];
            $total_debit = Transactions::where('COAID','10301')
                                        ->whereBetween('VDate',[$from, $to])
                                        ->sum('Debit');
            $total_credit = Transactions::where('COAID','10301')
                                        ->whereBetween('VDate',[$from, $to])
                                        ->sum('Credit');
            $total = 0;
            foreach ($transactions as $key => $cash) {
                if( $cash->Vtype == "Opening Balance" && $cash->Credit == 0){
                    array_push($balance, $cash->Debit);
                }
                elseif($cash->Vtype == "Opening Balance" && $cash->Debit == 0){
                    array_push($balance, $cash->Credit);
                }
                else{
                    if( count($balance) > 0 ){
                        if($cash->Credit == 0){
                        array_push($balance, $balance[count($balance) - 1] + $cash->Debit);
                        }elseif($cash->Debit == 0){
                            array_push($balance, $balance[count($balance) - 1] - $cash->Credit);
                        }
                    }else{
                        if($cash->Credit == 0){
                            array_push($balance,$cash->Debit);
                        }elseif($cash->Debit == 0){
                            array_push($balance,$cash->Credit);
                        }
                    }

                }
                $total = $balance[$key];

            }
            return response()->json(['balance' => $balance,'total_debit' => $total_debit, 'total_credit'=>$total_credit,'from' => $from, 'to' => $to, 'total' => $total, 'inventory_ledger' => $transactions],200);
        }
    }

    // Cashbook
    public function cash_book(){
        $cash_type = Accounts::where('HeadCode', 'like', '102030'. '%')->get();
        $company_info = Company::where('id',$this->user->company_id)->first();
        return view('accounts::cashbook', compact('cash_type','company_info'));
    }

    // Cash Book Filter
    public function cash_book_filter(Request $request){
        if($request->ajax()){
            $HeadCode = $request->get('HeadCode');
            $from = $request->get('from');
            $to = $request->get('to');
            $head_name = Accounts::where('HeadCode', $HeadCode)->first()->HeadName;


            if($from && $to){
                $cash_books = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->get();

                $total_debit = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->sum('Debit');
                $total_credit = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->sum('Credit');
            }else{
                $cash_books = Transactions::where('COAID', $HeadCode)->get();

                $total_debit = Transactions::where('COAID', $HeadCode)->sum('Debit');
                $total_credit = Transactions::where('COAID', $HeadCode)->sum('Credit');
            }

            $balance = [];
            $new_balance = [];
            $Vtype = [];

            foreach ($cash_books as $key => $cash) {
                if($cash->Vtype == 'DV'){
                    array_push($Vtype, 'Debit Voucher');
                }
                elseif($cash->Vtype == 'CRV'){
                    array_push($Vtype, 'Credit Voucher');
                }
                elseif($cash->Vtype == 'COV'){
                    array_push($Vtype, 'Contra Voucher');
                }
                elseif($cash->Vtype == 'JV'){
                    array_push($Vtype, 'Journal Voucher');
                }
                else{
                    array_push($Vtype, 'Opening Balance');
                }


                if( $cash->Vtype == "Opening Balance" && $cash->Credit == 0){
                    array_push($balance, $cash->Debit);
                }
                elseif($cash->Vtype == "Opening Balance" && $cash->Debit == 0){
                    array_push($balance, $cash->Credit);
                }
                else{
                    if( count($balance) > 0 ){
                        if($cash->Credit == 0){
                        array_push($balance, $balance[count($balance) - 1] + $cash->Debit);
                        }elseif($cash->Debit == 0){
                            array_push($balance, $balance[count($balance) - 1] - $cash->Credit);
                        }
                    }else{
                        if($cash->Credit == 0){
                            array_push($balance,$cash->Debit);
                        }elseif($cash->Debit == 0){
                            array_push($balance,$cash->Credit);
                        }
                    }
                }
            }

            return response()->json(['cash_books' => $cash_books, 'new_balance' => $new_balance,'Vtype' => $Vtype, 'balance' => $balance,'total_debit' => $total_debit, 'total_credit'=>$total_credit,'from' => $from, 'to' => $to,'head_name' => $head_name,'HeadCode' => $HeadCode],200);
        }
    }

    // Bank Book Functions
    public function bank_book(){
        $bank_type = Accounts::where('HeadCode', 'like', '102020'. '%')->get();
        $company_info = Company::where('id',$this->user->company_id)->first();
        return view('accounts::bank_book', compact('bank_type','company_info'));
    }

    // Bank Book Filter
    public function bank_book_filter(Request $request){
        if($request->ajax()){
            $HeadCode = $request->get('HeadCode');
            $from = $request->get('from');
            $to = $request->get('to');
            $head_name = Accounts::where('HeadCode', $HeadCode)->first()->HeadName;
            if($from && $to){
                $cash_books = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->get();

                $total_debit = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->sum('Debit');
                $total_credit = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->sum('Credit');
            }else{
                $cash_books = Transactions::where('COAID', $HeadCode)->get();

                $total_debit = Transactions::where('COAID', $HeadCode)->sum('Debit');
                $total_credit = Transactions::where('COAID', $HeadCode)->sum('Credit');
            }

            $balance = [];
            $new_balance = [];
            $Vtype = [];

            foreach ($cash_books as $key => $cash) {
                if($cash->Vtype == 'DV'){
                    array_push($Vtype, 'Debit Voucher');
                }
                elseif($cash->Vtype == 'CRV'){
                    array_push($Vtype, 'Credit Voucher');
                }
                elseif($cash->Vtype == 'COV'){
                    array_push($Vtype, 'Contra Voucher');
                }
                elseif($cash->Vtype == 'JV'){
                    array_push($Vtype, 'Journal Voucher');
                }
                else{
                    array_push($Vtype, 'Opening Balance');
                }


                if( $cash->Vtype == "Opening Balance" && $cash->Credit == 0){
                    array_push($balance, $cash->Debit);
                }
                elseif($cash->Vtype == "Opening Balance" && $cash->Debit == 0){
                    array_push($balance, $cash->Credit);
                }
                else{
                    if( count($balance) > 0 ){
                        if($cash->Credit == 0){
                        array_push($balance, $balance[count($balance) - 1] + $cash->Debit);
                        }elseif($cash->Debit == 0){
                            array_push($balance, $balance[count($balance) - 1] - $cash->Credit);
                        }
                    }else{
                        if($cash->Credit == 0){
                            array_push($balance,$cash->Debit);
                        }elseif($cash->Debit == 0){
                            array_push($balance,$cash->Credit);
                        }
                    }
                }
            }

            return response()->json(['cash_books' => $cash_books, 'new_balance' => $new_balance,'Vtype' => $Vtype, 'balance' => $balance,'total_debit' => $total_debit, 'total_credit'=>$total_credit, 'from' => $from, 'to' => $to,'head_name' => $head_name,'HeadCode' => $HeadCode],200);
        }
    }




    // Genereal Ladger Function
    public function general_ledger(){
        $IsGL = Accounts::where('IsGL',1)->get();
        $company_info = Company::where('id',$this->user->company_id)->first();
        return view('accounts::GeneralLadger.general_ladger', compact('IsGL','company_info'));
    }

    // Searching IsGL
    public function is_gl_search(Request $request){
        if($request->ajax()){
            $HeadCode = $request->get('HeadCode');
            $from = $request->get('from');
            $to = $request->get('to');

            $head_name = Accounts::where('HeadCode', $HeadCode)->first()->HeadName;

            $gl_transections = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->get();
            $total_debit = Transactions::where('COAID', $HeadCode)
                                            ->whereBetween('VDate',[$from, $to])
                                            ->sum('Debit');
            $total_credit = Transactions::where('COAID', $HeadCode)
                                        ->whereBetween('VDate',[$from, $to])
                                        ->sum('Credit');

            $balance = [];
            $new_balance = [];
            $Vtype = [];
            foreach ($gl_transections as $key => $gl_tran) {
                if($gl_tran->Vtype == 'DV'){
                    array_push($Vtype, 'Debit Voucher');
                }
                elseif($gl_tran->Vtype == 'CRV'){
                    array_push($Vtype, 'Credit Voucher');
                }
                elseif($gl_tran->Vtype == 'COV'){
                    array_push($Vtype, 'Contra Voucher');
                }
                elseif($gl_tran->Vtype == 'JV'){
                    array_push($Vtype, 'Journal Voucher');
                }
                else{
                    array_push($Vtype, 'Opening Balance');
                }

                if( $gl_tran->Vtype == "Opening Balance" && $gl_tran->Credit == 0){
                    array_push($balance, $gl_tran->Debit);
                }
                elseif($gl_tran->Vtype == "Opening Balance" && $gl_tran->Debit == 0){
                    array_push($balance, $gl_tran->Credit);
                }
                else{
                    if( count($balance) > 0 ){
                        if($gl_tran->Credit == 0){
                        array_push($balance, $balance[count($balance) - 1] + $gl_tran->Debit);
                        }elseif($gl_tran->Debit == 0){
                            array_push($balance, $balance[count($balance) - 1] - $gl_tran->Credit);
                        }
                    }else{
                        if($gl_tran->Credit == 0){
                            array_push($balance,$gl_tran->Debit);
                        }elseif($gl_tran->Debit == 0){
                            array_push($balance,$gl_tran->Credit);
                        }
                    }
                }
            }

            return response()->json(['gl_transaction' => $gl_transections, 'new_balance' => $new_balance,'Vtype' => $Vtype, 'balance' => $balance,'total_debit' => $total_debit, 'total_credit'=>$total_credit,'from' => $from, 'to' => $to,'head_name' => $head_name,'HeadCode' => $HeadCode],200);
        }
    }
}
