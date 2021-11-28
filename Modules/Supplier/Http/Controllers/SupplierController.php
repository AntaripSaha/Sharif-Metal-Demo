<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Supplier\Entities\Supplier;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
use Modules\Company\Entities\Company;
use Auth;
use DB;
use DataTables;
use App\User;
use Modules\Product\Entities\Product;

class SupplierController extends BaseController
{
    /**
     * Display a listing of the Supplier.
     */
    public function index(Request $request)
    {
        if(!$this->user->can('browse',app('Modules\Supplier\Entities\Supplier'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        $data = Supplier::with('accounts')->get();
        if ($data->count() > 0) {
            foreach ($data as $key => $dd) {
                $d[$key] = $dd->accounts->HeadCode;
                foreach ($d as $key => $value) {
                    $es[$key] = Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$value)->get();
                }
            }
            foreach ($es as $key => $value) {
                if ($value[0]['sum'] >0) {
                    $data[$key]['balance'] = $value[0]['sum'];
                }
                elseif($value[0]['sum']<0){
                    $data[$key]['balance'] = $value[0]['sum'];
                }else{
                    $data[$key]['balance'] = 0;
                }
            }
        }else{
            $data = [];
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
                    if($this->user->can('view_supplier',app('Modules\Supplier\Entities\Supplier')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                    }
                    if($this->user->can('edit_supplier',app('Modules\Supplier\Entities\Supplier')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> Edit</a>'; 
                    }
                    if($this->user->can('delete_supplier',app('Modules\Supplier\Entities\Supplier')) ){
                        $btnDel = '<a class="btn btn-danger delete-tr btn-sm" id="delete-tr-'.$row->id.'">Delete</a>';
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
        return view('supplier::index');
    }

    /**
     * Show the form for creating a new Supplier.
     */
    public function add_supplier(Request $request)
    {
        if(!$this->user->can('add_supplier',app('Modules\Supplier\Entities\Supplier'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{  
                $data=$request->all();
                if ($request->supplier_id) {
                    $data['supplier_id'] = $data['supplier_id'];
                }else{
                    $data['supplier_id'] = generateRandomStr(5);
                }
                $data['created_by'] =$this->user->id;
                $data['status']= 1;
                $supplier= Supplier::createSupplier($data);
                $supplier_id = $supplier->id;
                $coa = Accounts::where('HeadLevel',3)->where('HeadCode','Like','50101'.'%')->latest()->first();
                if ($coa!=NULL) {
                    $num = $coa['HeadCode'];
                    $int = (int)$num;
                    $headcode=$int+1;
                }
                else{
                    $headcode="501010001";
                }
                $c_acc=$supplier_id.'-'.$data['supplier_name'];
                $created_by=$this->user->id;
                $supplier_coa['HeadCode'] = $headcode;
                $supplier_coa['HeadName'] = $c_acc;
                $supplier_coa['PHeadName'] = 'Trade Payable';
                $supplier_coa['PHeadCode'] = '50101';
                $supplier_coa['HeadLevel'] = '3';
                $supplier_coa['IsActive'] = '1';
                $supplier_coa['IsTransaction'] = '1';
                $supplier_coa['IsGL'] = '0';
                $supplier_coa['HeadType'] = 'L';
                $supplier_coa['IsBudget'] = '0';
                $supplier_coa['IsDepreciation'] = '0';
                $supplier_coa['DepreciationRate'] = '0';
                $supplier_coa['supplier_id'] = $supplier_id;
                $supplier_coa['created_by'] = $this->user->id;
                Accounts::createAccounts($supplier_coa);
                Supplier::previous_balance_add($data['balance'],$supplier_id);
                return response()->json(['status'=>'success','data'=>$coa], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            return view('supplier::create');exit;
        }
    }

    public function supplier_ledger()
    {
        $transactions = Transactions::with('company')->where('COAID','Like','50200%')->get();
        
        $suppliers= Supplier::get();
        $companies = Company::where('parent_id','!=',0)->get();
        return view('supplier::supplier_ledger',compact('companies','transactions','suppliers'));exit;
    }
    public function view_ledgers(Request $request)
    {
        $data = $request->all();
        $sdate = $data['from'];
        $edate = $data['to'];
        $company_id = $data['company_id'];
        $supplier_id = $data['supplier_id'];

        $supplier = Supplier::select('supplier_name')->where('id',$data['supplier_id'])->first();
        $cus_coa = Accounts::select('HeadCode')->where('supplier_id',$data['supplier_id'])->first();
        if ($request->company_id && $request->from && $request->to && $request->supplier_id) {
            $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->whereBetween('Vdate',[$sdate,$edate])->where('company_id',$company_id)->get();
        }elseif($request->company_id && $request->supplier_id){
           $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->where('company_id',$company_id)->get(); 
        }elseif($request->supplier_id && $request->from && $request->to){
            $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->whereBetween('Vdate',[$sdate,$edate])->get();
        }elseif($request->supplier_id){
            $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->get();
        }else{
            $transactions = Transactions::with('company')->where('COAID','Like','50200'.'%')->where('company_id',$company_id)->get();
        }

        return view('supplier::ledger_view',compact('transactions','supplier'));
    }
    public function delete(Request $request,$sup_id){
        if(!$this->user->can('delete_supplier',app('Modules\Supplier\Entities\Supplier'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        try{
            $supplier=Supplier::findOrFail($sup_id);
            $sup_coa = Accounts::select('HeadCode')->where('supplier_id',$sup_id)->first();
            $transactions = Transactions::where('COAID',$sup_coa->HeadCode)->first();
            if ($transactions == null) {
                $supplier->delete();
                Accounts::where('supplier_id',$sup_id)->delete();
                return response()->json(['status'=>'success'], 200);
            }else{
                return response()->json(['status'=>'error'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'error'], 500);
        }   
    }

    // Supplier Products List
    // **********************
    public function supplierProducts(){
        $suppliers= Supplier::get();
        return view('supplier::SupplierProducts.index', compact('suppliers'));
    }

    public function supplier_product_view(Request $request){
        $supplier_id = $request->supplier_id;
        $from = $request->from;
        $to = $request->to;

        if($from && $to){
            $supplier_products = Product::with('category')
                                        ->where('supplier_id', $supplier_id)
                                        ->whereBetween('date', [$from,$to])
                                        ->get();
            return view('supplier::SupplierProducts.supplier_product_view', compact('supplier_products'));

        }else{
            $supplier_products = Product::with('category')->where('supplier_id', $supplier_id)->get();

            return view('supplier::SupplierProducts.supplier_product_view', compact('supplier_products'));
        }
    }
}
