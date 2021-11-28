<?php

namespace Modules\Bank\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Bank\Entities\Bank;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
use Modules\Company\Entities\Company;
use Auth;
use DB;
use DataTables;
use App\User;

class BankController extends BaseController
{
    public function index(Request $request)
    {
        if(!$this->user->can('browse',app('Modules\Bank\Entities\Bank'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        $company_id = Auth::user()->company_id;
        $data = Bank::with('accounts')->get();
        if ($data->count() > 0) {
            foreach ($data as $key => $dd) {
                $d[$key] = $dd->accounts->HeadCode;
                foreach ($d as $key => $value) {
                    $es[$key] = Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$value)->get();
                }
            }
            foreach ($es as $key => $value) {
                $data[$key]['balance'] = $value[0]['sum'];
            }
        }

        if ($request->ajax()) {          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btnDel ="";
                    $btn_view ='';
                    $btn_edit ='';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                        $btn_edit ='<a class="mr-2 btn btn-info btn-sm cp edit-tr" id="edit-tr-'.$row->id.'">Edit</a>';
                        $btnDel = '<a class="btn btn-danger delete-tr btn-sm" id="delete-tr-'.$row->id.'">Delete</a>';
                    }
                    if($this->user->can('add_bank',app('Modules\Bank\Entities\Bank')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                    }
                    if($this->user->can('edit_bank',app('Modules\Bank\Entities\Bank')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> Edit</a>'; 
                    }
                    if($this->user->can('delete_bank',app('Modules\Bank\Entities\Bank')) ){
                        $btnDel ='<a class="mr-2 cp delete-tr btn btn-danger btn-sm" id="delete-tr-'.$row->id.'"> Delete</a>'; 
                    }
                    return $btn_view.$btn_edit.$btnDel;   
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->editColumn('status', function ($row) {
                        if ($row->status == 1) {
                            $data = 'Active';
                        }else{
                            $data = 'Inactive';
                        }
                    return $data;
                })
                ->rawColumns(['action','input','status.name'])
                ->make(true); exit;
        }
        $total = $data->count();
        return view('bank::index',compact('total'));
    }

    public function addBank(Request $request)
    {
        if(!$this->user->can('add_bank',app('Modules\Bank\Entities\Bank'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{  
                $data=$request->all();
                if ($request->sign) {
                    $img = img_process($data['sign'],'app/public/uploads');
                    $data['sign'] = $img;
                }else{
                    $data['sign'] = NULL;
                }
                $data['bank_id'] = generateRandomStr(8);
                $bank= Bank::createBank($data);
                $bank_id = $bank->id;
                $coa = Accounts::where('HeadLevel',3)->where('HeadCode','Like','1020202%')->latest()->first();
                if ($coa!=NULL) {
                    $headcode=$coa['HeadCode']+1;
                }
                else{
                    $headcode="102020201";
                }
                $bank_coa['HeadCode'] = $headcode;
                $bank_coa['HeadName'] = $data['bank_name'].'-'.$data['account_no'];
                $bank_coa['PHeadName'] = 'Cash At Bank';
                $bank_coa['PHeadCode'] = '10202';
                $bank_coa['HeadLevel'] = '3';
                $bank_coa['IsActive'] = '1';
                $bank_coa['IsTransaction'] = '1';
                $bank_coa['IsGL'] = '0';
                $bank_coa['HeadType'] = 'A';
                $bank_coa['IsBudget'] = '0';
                $bank_coa['IsDepreciation'] = '0';
                $bank_coa['DepreciationRate'] = '0';
                $bank_coa['bank_id'] = $bank->id;
                $bank_coa['created_by'] = $this->user->id;
                Accounts::createAccounts($bank_coa);
                if ($data['balance'] !== 'null') {
                    Bank::previous_balance_add($data['balance'],$bank_id);
                }
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            return view('bank::create');exit;
        }
    }


    public function viewBank($id)
    {
        if(!$this->user->can('view_bank',app('Modules\Bank\Entities\Bank'))){
            return response()->json(['status'=>'permission denied'], 401);
        }
        $account_details = Bank::where('id', $id)->first();
        $bank_coa = Accounts::select('HeadCode')->where('bank_id',$id)->first();
        $tr_balance = Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$bank_coa->HeadCode)->get();
        $balance = $tr_balance[0]['sum'];
        return view('bank::view', compact('account_details','balance'));
    }

    public function editBank(Request $request,$bank_id){
        if(!$this->user->can('edit_bank',app('Modules\Bank\Entities\Bank'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){

            try{  
                $data=$request->all();              
                $data['updated_by']=$request->user()->id;
                Bank::updateBank($data,$bank_id);
                return response()->json(['status'=>'success'], 200);

            }catch(\Exception $e){

                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            try{
                $bank_details = Bank::where('id', $bank_id)->first();
                return view('bank::edit',compact('bank_details'));exit;

            }catch(\Exception $e){

                return response()->json(['status'=>'error'], 500);
            }
        }
    }

    // Delete Function
    public function deleteBankAccount(Request $request,$bank_id){
        if(!$this->user->can('delete_bank',app('Modules\Bank\Entities\Bank'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        try{
            $bank = Bank::where('id',$bank_id)->first();
            $bank_coa = Accounts::select('HeadCode')->where('bank_id',$bank_id)->first();
            $transactions = Transactions::where('COAID',$bank_coa->HeadCode)->first();
            if ($transactions == null) {
                $bank->delete();
                return response()->json(['status'=>'success','d'=>$transactions], 200);
            }else{
                return response()->json(['status'=>'error','d'=>$transactions], 200);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'error'], 500);
        }   
    }
    public function create_transaction(Request $request)
    {
        if(!$this->user->can('browse_transaction',app('Modules\Bank\Entities\Bank'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{  
                $data=$request->all();
                $bank = Bank::where('bank_id',$data['bank_id'])->select('bank_name')->first();
                $headname = $bank->bank_name.'-'.$bank->account_no;
                $coa = Accounts::where('HeadName',$headname)->select('HeadCode')->first();
                $data['Vtype'] = 'Bank Transaction';
                $data['COAID'] = $coa->HeadCode;
                if ($data['ac_type'] == 1) {
                    $data['Debit'] = $data['amount'];
                    $data['Credit'] = 0;
                }else{
                    $data['Credit'] = $data['amount'];
                    $data['Debit'] = 0;
                }
                $data['IsPosted'] = 1;
                $data['IsAppove'] = 1;
                $data['created_by'] = $this->user->id;
                $data['updated_by'] = $this->user->id;
                $data['company_id'] = $this->user->company_id;
                Transactions::createTransaction($data);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            $banks = Bank::get();
            return view('bank::create_transaction',compact('banks'));exit;
        }
    }
    public function ledgers(Request $request)
    {   if(!$this->user->can('browse_ledger',app('Modules\Bank\Entities\Bank'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        $edate = date('Y-m-d');
        $sdate = date('Y-m-d');
        $data = Transactions::whereBetween('VDate', [$sdate, $edate])->get();
        $company_info = Company::where('id',$this->user->company_id)->first();
        $banks = Bank::get();
        return view('bank::ledgers',compact('company_info','edate','data','banks'));
    }
    public function view_ledgers(Request $request)
    {
        $data = $request->all();
        $sdate = $data['from'];
        $edate = $data['to'];
        $bank = Bank::select('bank_name')->where('id',$data['bank_id'])->first();
        $bank_coa = Accounts::select('HeadCode')->where('bank_id',$data['bank_id'])->first();
        $transactions = Transactions::where('COAID',$bank_coa->HeadCode)->whereBetween('Vdate',[$sdate,$edate])->where('company_id',$this->user->company_id)->get();
        return view('bank::ledger_view',compact('transactions','bank'));
    }
}
