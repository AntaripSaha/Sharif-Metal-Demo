<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Customer\Entities\Customer;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
use Modules\Company\Entities\Company;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerImport;
use PDF;
use Auth;
use DB;
use DataTables;
use App\User;

class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        if(!$this->user->can('browse',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        $company_id = Auth::user()->company_id;
        // $data = Customer::with('accounts')->select('id','customer_id', 'customer_name')->get();
        $data = Customer::select('id','customer_id', 'customer_name')->get();


        // if ($data->count() > 0) {
        //     foreach ($data as $key => $dd) {
        //         $d[$key] = $dd->accounts->HeadCode;
        //         foreach ($d as $key => $value) {
        //             $es[$key] = Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$value)->get();
        //         }
        //     }
        //     foreach ($es as $key => $value) {
        //         if ($value[0]['sum'] >0) {
        //             $data[$key]['balance'] = $value[0]['sum'];
        //         }
        //         elseif($value[0]['sum']<0){
        //             $data[$key]['balance'] = $value[0]['sum'];
        //         }else{
        //             $data[$key]['balance'] = 0;
        //         }
        //     }
        // }
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
                    if($this->user->can('view_customer',app('Modules\Customer\Entities\Customer')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> <i class="fas fa-eye"></i> </a>';
                    }
                    if($this->user->can('edit_customer',app('Modules\Customer\Entities\Customer')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> <i class="fas fa-edit"></i> </a>';
                    }
                    if($this->user->can('delete_customer',app('Modules\Customer\Entities\Customer')) ){
                        $btnDel = '<a class="btn btn-danger delete-tr btn-sm" id="delete-tr-'.$row->id.'"> <i class="far fa-trash-alt"></i> </a>';
                    }
                    return $btn_view.$btn_edit.$btnDel;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        return view('customer::index');
    }

    public function addCustomer(Request $request)
    {
        if(!$this->user->can('add_customer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }

        if($request->isMethod('post')){
            try{
                $data = $request->all();
                $data['created_by'] =$this->user->id;
                $data['status']= 2;

                $customer = Customer::createCustomer($data);
                $customer_id = $customer->id;
                $coa = Accounts::where('HeadLevel',4)->where('HeadCode','Like','10201'.'%')->orderBy('HeadCode', 'desc')->first();

                $account_headcode = '';
                if ($coa) {
                     $account_headcode = $coa['HeadCode']+1;
                }
                 else{
                     $account_headcode = "102010000001";
                 }
                
                
                $c_acc=$customer_id.'-'.$data['customer_name'];
                $created_by=$this->user->id;
                $customer_coa['HeadCode']           = (string)$account_headcode;
                $customer_coa['HeadName']           = $c_acc;
                $customer_coa['PHeadName']          = 'Trade Receivable';
                $customer_coa['PHeadCode']          = '10201';
                $customer_coa['HeadLevel']          = '4';
                $customer_coa['IsActive']           = '1';
                $customer_coa['IsTransaction']      = '1';
                $customer_coa['IsGL']               = '0';
                $customer_coa['HeadType']           = 'A';
                $customer_coa['IsBudget']           = '0';
                $customer_coa['IsDepreciation']     = '0';
                $customer_coa['DepreciationRate']   = '0';
                $customer_coa['customer_id']        = $customer_id;
                $customer_coa['created_by']         = $this->user->id;

                Accounts::createAccounts($customer_coa);
                if($data['balance']){
                    Customer::previous_balance_add($data['balance'],$customer_id);
                }
                return response()->json(['status'=>'success','data'=>$account_headcode], 200);
                
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $sellers = User::where('role_id', 4)->orWhere('is_manager_seller', 1)->get();
            return view('customer::create',compact('sellers'));exit;
        }
    }

    public function viewCustomer($customer_id)
    {
        if(!$this->user->can('browse_customer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        
        $customer = Customer::with('accounts','seller')->where('id',$customer_id)->first();
        
        
        $pre_balance = Transactions::select('Debit')->where('COAID',$customer->accounts->HeadCode)->where('Vtype','PR Balance')->first();
        
        
        
        
        
        
        return view('customer::view',compact('customer','pre_balance'));
    }

    public function updateCustomer(Request $request,$customer_id )
    {
        if(!$this->user->can('browse_customer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
            if($request->isMethod('post')){
            try{
                $data=$request->all();
                Customer::updateCustomer($data,$customer_id);
                $cus_coa = Accounts::select('HeadCode')->where('customer_id',$customer_id)->first();
                $coa = $cus_coa->HeadCode;
                $v_no = Transactions::select('VNo')->where('COAID',$coa)->where('Vtype','PR Balance')->first();

                if( $v_no ){
                    Transactions::where('VNo',$v_no->VNo)->where('Debit','>','0.00')->update(['Debit'=>$data['balance']]);
                    Transactions::where('VNo',$v_no->VNo)->where('Credit','>','0.00')->update(['Credit'=>$data['balance']]);
    
                    // Transactions::where('VNo',$v_no->VNo)->update(['Debit'=>$data['balance']]);
                    // Transactions::where('VNo',$v_no->VNo)->update(['Credit'=>$data['balance']]);
                }
                

                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $customer = Customer::with('accounts','seller')->where('id',$customer_id)->first();
            $pre_balance = Transactions::select('Debit')->where('COAID',$customer->accounts->HeadCode)->where('Vtype','PR Balance')->first();
            $sellers = User::whereIn('role_id',[4,10])->orderBy("id","desc")->get();
            return view('customer::edit',compact('customer','pre_balance','sellers'));
        }
    }

    public function paid_customer(Request $request)
    {
        if(!$this->user->can('browse_paidcustomer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        $company_id = Auth::user()->company_id;
        $cus = Customer::with('accounts')->get();
        if ($cus->count() > 0) {
            foreach ($cus as $key => $dd) {
                $d = $dd->accounts->HeadCode;
                $es= Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$d)->get();

                $balance = $es['0']['sum'];
                if ($balance < 0 || $balance == null) {
                    $data[$key] = $dd;
                    $data[$key]['balance'] = $es['0']['sum'];
                }
                if($balance == null){
                    $data[$key] = $dd;
                    $data[$key]['balance'] = 0;
                }
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
                    }
                    if($this->user->can('view_customer',app('Modules\Customer\Entities\Customer')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                    }
                    return $btn_view;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        $total = $cus->count();
        return view('customer::paid_customer',compact('total'));
    }

    public function credit_customer(Request $request)
    {
        if(!$this->user->can('browse_creditcustomer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        $company_id = Auth::user()->company_id;
        $cus = Customer::with('accounts')->get();
        if ($cus->count() > 0) {
            foreach ($cus as $key => $dd) {
                $d= $dd->accounts->HeadCode;
                $es= Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$d)->get();
                $balance = $es['0']['sum'];
                if ($balance > 0 || $balance !== null) {
                    $data[$key] = $dd;
                    $data[$key]['balance'] = $balance;
                }
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
                    }
                    if($this->user->can('view_customer',app('Modules\Customer\Entities\Customer')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                    }
                    return $btn_view;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        $total = $cus->count();
        return view('customer::credit_customer',compact('total'));
    }

    public function customer_advance(Request $request)
    {
        if(!$this->user->can('advancecustomer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                if ($data['advance_type'] == 1) {
                    $dr = $data['amount'];
                    $tp = 'd';
                }else{
                    $cr = $data['amount'];
                    $tp = 'c';
                }
                $created_by=$this->user->id;
                $company_id = $this->user->company_id;
                $transaction_id=generateRandomStr(10);
                $customer_id = $data['customer_id'];
                $cusifo = Customer::find($customer_id);
                $headn = $customer_id.'-'.$cusifo->customer_name;
                $coainfo = Accounts::select('HeadCode')->where('HeadName',$headn)->first();
                $customer_headcode = $coainfo->HeadCode;
                $customer_accledger = array(
                    'VNo'            =>  $transaction_id,
                    'Vtype'          =>  'Advance',
                    'VDate'          =>  date("Y-m-d"),
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer Advance For  '.$cusifo->customer_name,
                    'Debit'          =>  (!empty($dr)?$dr:0),
                    'Credit'         =>  (!empty($cr)?$cr:0),
                    'IsPosted'       => 1,
                    'created_by'       => $created_by,
                    'updated_by'       => $created_by,
                    'IsAppove'       => 1,
                    'company_id'     => $company_id
                );
                $cc = array(
                    'VNo'            =>  $transaction_id,
                    'Vtype'          =>  'Advance',
                    'VDate'          =>  date("Y-m-d"),
                    'COAID'          =>  1020101,
                    'Narration'      =>  'Cash in Hand  For '.$cusifo->customer_name.' Advance',
                    'Debit'          =>  (!empty($dr)?$dr:0),
                    'Credit'         =>  (!empty($cr)?$cr:0),
                    'IsPosted'       =>  1,
                    'created_by'       =>  $created_by,
                    'updated_by'       =>  $created_by,
                    'IsAppove'       =>  1,
                    'company_id'     => $company_id
                );
                Transactions::createTransaction($customer_accledger);
                Transactions::createTransaction($cc);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $customers = Customer::get();
            return view('customer::create_advance',compact('customers'));exit;
        }
    }

    public function customer_ledger()
    {
        $transactions = Transactions::with('company')->where('COAID','Like','1020300%')->get();
        $customers= Customer::get();
        $companies = Company::where('parent_id','!=',0)->get();
        return view('customer::customer_ledger',compact('companies','transactions','customers'));exit;
    }
    public function view_ledgers(Request $request)
    {
        $data = $request->all();
        $sdate = $data['from'];
        $edate = $data['to'];
        $company_id = $data['company_id'];
        $customer_id = $data['customer_id'];

        $customer = Customer::select('customer_name')->where('id',$data['customer_id'])->first();
        $cus_coa = Accounts::select('HeadCode')->where('customer_id',$data['customer_id'])->first();

        if ($request->company_id && $request->from && $request->to && $request->customer_id) {
            $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->whereBetween('Vdate',[$sdate,$edate])->where('company_id',$company_id)->get();
        }elseif($request->company_id && $request->customer_id){
           $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->where('company_id',$company_id)->get();
        }elseif($request->customer_id && $request->from && $request->to){
            $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->whereBetween('Vdate',[$sdate,$edate])->get();
        }elseif($request->customer_id){
            $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->get();
        }else{
            $transactions = Transactions::with('company')->where('COAID','Like','1020300'.'%')->where('company_id',$company_id)->get();
        }
        return view('customer::ledger_view',compact('transactions','customer'));
    }
    public function deleteCustomer(Request $request,$cus_id){
        if(!$this->user->can('delete_customer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        try{
            $customer=Customer::findOrFail($cus_id);
            $cus_coa = Accounts::select('HeadCode')->where('customer_id',$cus_id)->first();
            $transactions = Transactions::where('COAID',$cus_coa->HeadCode)->first();
            if ($transactions == null) {
                $customer->delete();
                Accounts::where('customer_id',$cus_id)->delete();
                return response()->json(['status'=>'success'], 200);
            }else{
                return response()->json(['status'=>'error'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'error'], 500);
        }
    }

    public function import_file(Request $request)
    {
        if(!$this->user->can('add_customer',app('Modules\Customer\Entities\Customer'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{
                $files = $request->file('import_file');

                $import = Excel::import(new CustomerImport,$files);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            return view('customer::import_file');exit;
        }
    }
}
