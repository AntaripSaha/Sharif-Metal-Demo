<?php

namespace Modules\Seller\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Customer\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Seller\Entities\SellRequest;
use Modules\Seller\Entities\RequestProduct;
use Modules\Accounts\Entities\Transactions;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\CheckList;
use Modules\Company\Entities\Company;
use Modules\Bank\Entities\Bank;
use Modules\Warehouse\Entities\WarehouseProducts;
use Modules\Warehouse\Entities\WarehouseInserts;
use Modules\Warehouse\Entities\Warehouse;
// use App\Exports\UsersExport;
use App\Exports\SaleRequestDetailsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

use PDF;
use App\User;
use Carbon\Carbon;
use DB;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SellerController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        
        if(!$this->user->can('browse',app('Modules\Seller\Entities\Seller'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        $data = SellRequest::with('customer','seller')->where('is_approved',0)->where("is_rejected",false)->get();
        $user_id = Auth::user()->id;

        $child_users = User::where('parent_id', $user_id)->select('id')->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btn_view ='';
                    $btn_print = '';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    }
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    return $btn;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->editColumn('seller',function($row){
                        $seller_code = $row->seller->user_id;
                        $seller_name = $row->seller->name;
                        return  $seller_code.' - '.$seller_name;
                })
                ->rawColumns(['action','input','seller'])
                ->make(true); exit;
        }
        return view('seller::index', compact('child_users'));
    }

    // Invoice No
    private function voucher_no(){
        $prefix = 'AB';
        $serialNo = 1;
        $current_voucher_no = SellRequest::max('voucher_no');
        if($current_voucher_no == null){
            $voucher_no = $prefix.'-000'.$serialNo;
        }else{
            $voucherIncrementNumber = explode("-",$current_voucher_no);
            $currentPrefix = $voucherIncrementNumber[0];
            $currentSerialNo = $voucherIncrementNumber[1];
            $currentSerialNo++;
            if($currentSerialNo >= 9999){
                $currentPrefix++;
                $currentSerialNo = 1;
            }

            if($currentSerialNo <= 9){
                $nextSerialNo = '000'.$currentSerialNo;
            }
            elseif($currentSerialNo <= 99){
                $nextSerialNo = '00'.$currentSerialNo;
            }
            elseif($currentSerialNo <= 999){
                $nextSerialNo = '0'.$currentSerialNo;
            }
            else{
                $nextSerialNo = $currentSerialNo;
            }
            $voucher_no = $currentPrefix."-".$nextSerialNo;
        }
        return $voucher_no;
    }

    // Invoice No
    private function bill_no(){
        $company_id = $this->user->company_id;
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;
        $month = '';
        if($now->month <= 9){
            $month = '0'.$now->month;
        }
        else{
            $month = $now->month;
        }
        $bill_no_result = DB::select("SELECT * FROM `sell_requests` ORDER BY `id` DESC LIMIT 1");
        $bill_no = $bill_no_result[0]->req_id;
        if($bill_no == null){
            $bill_no = 'BL-'.$now->year.$month.'-00001';
            return $bill_no;
        }else{
            $invIncrementNumber = explode("-",$bill_no);
            $invNumber = (int)$invIncrementNumber[2];
            $invNumber++;

            if($invNumber <=9 ){
                $invNumber = '0000'.$invNumber;
            }else if($invNumber <= 99){
                $invNumber = '000'.$invNumber;
            }else if($invNumber <= 999){
                $invNumber = '00'.$invNumber;
            }else if($invNumber <= 9999){
                $invNumber = '0'.$invNumber;
            }else{
                $invNumber = $invNumber;
            }

            $bill_no = 'BL-'.$now->year.$month.'-'.$invNumber;
            return $bill_no;
        }
    }

    public function add_sell(Request $request)
    {     
        
        if(!$this->user->can('add_sells',app('Modules\Seller\Entities\Seller'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{
                self::voucher_no();
                $voucher_no = $this->voucher_no();

                self::bill_no();
                $bill_no = $this->bill_no();
                // return $voucher_no;
                $data=$request->all();
                // $data['req_id'] = generateRandomStr(8);
                $data['req_id'] = $bill_no;


                if ($this->user->isOfficeAdmin()) {
                    $data['is_approved'] = 1;
                    $data['approved_by'] = $this->user->id;
                    // $data['voucher_no']= 'v-'.generateRandomStr(8);
                   
                }
                else{
                    $data['is_approved'] = 0;
                    $data['approved_by'] = null;
                }
                
                $now = Carbon::now();
                $currentYear = $now->year;
                $currentMonth = $now->month;
                $currentDay = $now->day;
                $data['req_id'] = "BL-" . $currentYear . $currentMonth . $currentDay .'-'. explode("-",$voucher_no)[1];
                $cus = Customer::where('id',$data['customer_id'])->first();
                $seller_id = $cus->seller_id;
                $data['voucher_no'] = $voucher_no;

                $data['seller_id'] = $seller_id;
                $data['due_amount'] = $request->due_amount;

                $re_id = SellRequest::createRequest($data);
                foreach ($data['product_id'] as $key => $value) {
                    $n_data['product_id'] = $data['product_id'][$key];
                    $product_info = Product::where('id', $n_data['product_id'])->first();
                    $n_data['unit_price'] = $product_info->price;
                    $n_data['production_price'] = $product_info->production_price;
                    // $n_data['head_code'] = $data['head_code'][$key];
                    $n_data['qnty'] = $data['qnty'][$key];
                    $n_data['prod_disc'] = $data['prod_disc'][$key];
                    $n_data['req_id'] = $re_id;
                    RequestProduct::createProductReq($n_data);
                }
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $role_id = $this->user->role_id;
            if ($this->user->role_id != 4) {
                $sellers = User::where('role_id',4)->get();
                $customers = Customer::get();
            }else{

                $sellers = $this->user->id;

                $customers = Customer::where('seller_id',$this->user->id)->get();
            }

            $products = Product::all();
            $companies = Company::where('parent_id','!=',0)->get();
            return view('seller::new_sellreq',compact('customers','products','companies','sellers','role_id'));exit;
        }
    }

 //Edit Sell Request Start
    public function edit_requisition_details(Request $request, $id){
        if($request->isMethod('post')){
            try{
                $data = $request->all();
                //Update Sell Request Table Data
                $updateSellRequest = DB::table('sell_requests')
                                    ->where('id', $id)
                                    ->update([
                                        'amount' => $data['amount'], 'discount' => $data['discount'], 'remarks' => $data['remarks'],
                                        'transp_name' => $data['transp_name'], 'sale_disc' => $data['sale_disc']
                                    ]);
                // Now Delete All Product List for This ID First
                $delete_prev_data = RequestProduct::where('req_id', $id)->delete();
                if($delete_prev_data){
                    // Insert New Data on product_request_details
                    foreach ($data['product_id'] as $key => $value) {
                        $n_data['product_id'] = $data['product_id'][$key];
                        $n_data['qnty'] = $data['qnty'][$key];
                        $n_data['prod_disc'] = $data['prod_disc'][$key];
                        $n_data['req_id'] = $id;
                        RequestProduct::createProductReq($n_data);
                    }
                    return redirect()->route('seller.index');
                }else{
                    return "Error_Occure. Contact With Developers";
                    exit();
                }
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $sale_request_master = SellRequest::find($id);
            $product_details = RequestProduct::with('products')->where('req_id', $id)->get();
            $customer_name = Customer::select('customer_name')->where('id', $sale_request_master->customer_id)->first();
            $seller_info = User::select('name', 'user_id')->where('id', $sale_request_master->seller_id)->first();
            $products = Product::all();

            return view('seller::edit_sale_requisition', compact('sale_request_master', 'product_details',
                                                             'customer_name', 'seller_info', 'products', 'id'));
        }
    }
    // Edit Sale Request End

    public function sell_req_details($id)
    {
        $userType = $this->user->isOfficeAdmin();
        $cus_id = SellRequest::select('customer_id','seller_id','v_date','amount','is_approved','is_rejected','remarks','sale_disc','sale_discount_overwrite','discount','due_amount','transp_name')->with('seller')->where('id', $id)->first();
        $data = Customer::with('accounts')->where('id',$cus_id->customer_id)->first();
        $seller = User::select('name','user_id')->where('id',$cus_id->seller_id)->first();
        $cus_name = !empty($data) ? $data->customer_name : "";
        $seller_name = $seller->user_id.'-'.$seller->name;
        $v_date = $cus_id->v_date;
        $is_approved = $cus_id->is_approved;
        
        $coa = !empty($data) ? $data->accounts->HeadCode : "";
        
        
        
        $due_amount = Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$coa)->get();
        $amt = $due_amount[0]->sum;
        $req_id = $id;
        $remarks = $cus_id->remarks;
        $req_products = RequestProduct::with('products')->where('req_id', $id)->get();
        $total_amount = $cus_id->amount;
        $sale_requisition_id = $id;

        return view('seller::sale_req_details', compact('userType','amt','req_id','req_products','cus_name','v_date','total_amount','is_approved','seller_name','remarks','cus_id','sale_requisition_id'));

    }

    //Export Excel
    public function sale_request_details_export_excel($id){
        $sale_request   = new SaleRequestDetailsExport();

        $data           = SellRequest::find($id);
        $products       = RequestProduct::with('products')->where('req_id', $id)->get();
        $customer_info  = Customer::with('accounts')->where('id',$data->customer_id)->first();
        $seller_info    = User::select('name','user_id')->where('id',$data->seller_id)->first();

        return Excel::download($sale_request->getDownloadByQuery($data, $products, $customer_info, $seller_info), 'SaleRequest.xlsx');
    }

    // Print Sale Request Details Before Activation
    public function SaleRequestPrint($id){
        $sale_request = SellRequest::with('seller')->where('id', $id)->first();
        $req_products = RequestProduct::with('products')->where('req_id', $id)->get();

        //Check Due Amount is Saved or Not
        
            //    Get Accounts Data on this sale request customer ID
            $data = Customer::with('accounts')->where('id', $sale_request->customer_id)->first();
            $seller = User::select('name','user_id')->where('id',$data->seller_id)->first();
            $coa = $data->accounts->HeadCode;
            $due_amount = Transactions::select(DB::raw('SUM(Debit)-SUM(Credit) as sum'))->where('COAID',$coa)->get();
            $amt = $due_amount[0]->sum;

            // return $sale_request;

            $pdf_style = '<style>
                    *{
                        font-size:12px;
                    }
                    table,td, th {
                        border: 1px solid black;
                        border-collapse: collapse;
                        width:100%;
                    }
                </style>';
                $title = 'O . R . S';
                $company_info = Company::where('id',$this->user->company_id)->first();
                $pdf = PDF::loadView('seller::print_sale_request',compact('sale_request','req_products','company_info','pdf_style','title','data','seller','amt'));
                $pdf->setPaper('A4', 'potrait');
                $name = "OrderRequisitionDetails-".$id.".pdf";
                return $pdf->stream($name, array("Attachment" => false));
        
    }


    public function sold_details($id)
    {
        $cus_id = SellRequest::select('customer_id','req_id','seller_id','v_date','del_amount','is_approved',
                                        'remarks','del_discount','company_id','sale_disc', 'sale_discount_overwrite')->with('seller')->where('id', $id)->first();
        $data = Customer::with('accounts')->where('id',$cus_id->customer_id)->first();
        $seller = User::select('name')->where('id',$cus_id->seller_id)->first();
        $cus_name = $data->customer_name;
        $seller_name = $seller->name;
        $v_date = $cus_id->v_date;
        $request_id = $cus_id->req_id;
        $req_id = $id;
        $remarks = $cus_id->remarks;
        $sale_disc = $cus_id->sale_disc;
        $sale_discount_overwrite = $cus_id->sale_discount_overwrite;
        $req_products = RequestProduct::with('products')->where('req_id', $id)->get();
        $total_amount = (int)$cus_id->del_amount;
        $dis_amount = (int)$cus_id->del_discount;
        $edate = date('Y-m-d');
        $company_info = Company::where('id',$cus_id->company_id)->first();
        return view('seller::delivered_prod', compact('sale_disc','sale_discount_overwrite','dis_amount','req_id','req_products','cus_name','v_date','total_amount','seller_name','remarks','request_id','company_info','edate','cus_id'));

    }
    /*Approve request*/
    public function request_approve($id)
    {
        self::voucher_no();
        $voucher_no_n = $this->voucher_no();
        try{
            $approved_by = $this->user->id;
            // $voucher_no = 'v-'.generateRandomStr(8);
            $voucher_no = $voucher_no_n;

            $update = SellRequest::where('id', $id)->update(['is_approved' => 1, 'approved_by' => $approved_by]);
            return response()->json(['status'=>'success'], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }
    
    
    //reject request
    public function request_reject($id){
        try{
            $update = SellRequest::where('id', $id)->update(['is_rejected' => 1]);
            return response()->json(['status'=>'success'], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }
    
    //cancel reject request
    public function cancel_request_reject($id){
        try{
            $update = SellRequest::where('id', $id)->update(['is_rejected' => false]);
            return response()->json(['status'=>'success'], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }
    
    //sales_discount_edit function start mohan
    public function sales_discount_edit(Request $request , $id){
       $req_products = RequestProduct::with('products')->where('req_id', $id)->get();
       $total = 0;
       foreach ($req_products as $key => $req_product) {
               
         $single_product_price =  $req_product->del_qnt * $req_product->unit_price ;
                 $total += $single_product_price;
            }
        
        $edit_percentage = SellRequest::select("id","sale_disc","voucher_no","amount","discount","del_discount","del_amount")->where("id",$id)->first();
        return view('seller::edit-percentage', compact('edit_percentage' ,'req_products','total'));

    }
    //sales_discount_edit function ends


    //sales_discount_update function start
    public function sales_discount_update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            "sale_disc"     => "required",
            "del_discount"  => "required",
            "del_amount"    => "required",

        ]);

        if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {

                try {
                    $sell_request = SellRequest::find($id);
                    $sell_request->sale_disc = $request->sale_disc;
                    $sell_request->del_discount = $request->del_discount;
                    $sell_request->del_amount = $request->del_amount;

                    if ($sell_request->save()) {

                        if ($sell_request->save()) {
                            Session::flash('message', 'sales discount updated successfully...!');
                            return back();
                        }
                    }
                } catch (Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 200);
                }
            }
        $new_sale_disc = $request->get('sale_disc');
         
    }
    //sales_discount_update function ends


    /*View the sales*/
    public function manage_sales(Request $request)
    {   
        // if(!$this->user->can('view_sales',app('Modules\Seller\Entities\Seller')) ){
        //     return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        // }
        $data = SellRequest::with('customer','seller')->where('is_delivered',1)->where('is_rejected',0)->get();
        // return $data;
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btn_view ='';
                    if($this->user->isOfficeAdmin()){ 
                       
                        $btn_edit_discount ='<a class="mr-2 btn btn-secondary btn-sm"  data-content="'.route('sales.discount.edit', $row->id).'" data-target="#myModal"  data-toggle="modal"> Edit</a>';
                        $btn_reject ='<a class="mr-2 cp view-tr btn btn-danger btn-sm" href='.route('rejected.sales',$row->id).'> Reject</a>';
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn_print = '<a class="mr-2 cp print-tr btn btn-warning btn-sm" id="print-tr-'.$row->id.'"> Bill</a>';
                        $btn_bill = '<a class="mr-2 cp view-tr btn btn-info btn-sm" href=' . route('bill.edit', $row->id) . '>Edit Bill</a>';
                        $btn_printChalan = '<a class="mr-2 cp printchalan-tr btn btn-info btn-sm" id="printchalan-tr-'.$row->voucher_no.'"> Challan</a>';

                        $btn = $btn_printChalan.$btn_print.$btn_bill.$btn_view.$btn_reject.$btn_edit_discount;
                    }
                    //role id 19 = Bill
                    if($this->user->role->id == 19){ 
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn_print = '<a class="mr-2 cp print-tr btn btn-warning btn-sm" id="print-tr-'.$row->id.'"> Bill</a>';;
                        $btn_bill = '<a class="mr-2 cp view-tr btn btn-info btn-sm" href=' . route('bill.edit', $row->id) . '>Edit Bill</a>';
                        $btn = $btn_print.$btn_bill.$btn_view;
                    }
                    //role id 18 = Chalan
                    if($this->user->role->id == 18){ 
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn_printChalan = '<a class="mr-2 cp printchalan-tr btn btn-info btn-sm" id="printchalan-tr-'.$row->voucher_no.'"> Challan</a>';
                        $btn = $btn_printChalan.$btn_view;
                    }
                    return $btn;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->editColumn('seller',function($row){
                            $seller =  $row->seller->user_id.'-'.$row->seller->name;
                            return $seller;
                    })
                    ->editColumn('customer',function($row){
                            $customer =  $row->customer->customer_id.'-'.$row->customer->customer_name;
                            return $customer;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        return view('seller::manage_sales' , compact('data') );
    }

    public function manage_chalan(Request $request)
    {
        if(!$this->user->can('browse_sells',app('Modules\Seller\Entities\Seller'))){
            // This Line is Comment out for one time solution.
            // Make sure next time do the correct Solution
            // return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));

        }
        $data = SellRequest::with('customer','seller')->where('is_delivered',1)->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btn_view ='';
                    // if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn_print = '<a class="mr-2 cp print-tr btn btn-warning btn-sm" id="print-tr-'.$row->voucher_no.'"> Print</a>';
                        $btn = $btn_print.$btn_view;
                    // }
                    // if($this->user->can('view_sales',app('Modules\Seller\Entities\Seller')) ){
                    //     $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                    //     $btn_print = '<a class="mr-2 cp print-tr btn btn-warning btn-sm" id="print-tr-'.$row->voucher_no.'"> Print</a>';
                    //     $btn = $btn_print.$btn_view;
                    // }
                    return $btn;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->editColumn('seller',function($row){
                            $seller =  $row->seller->user_id.'-'.$row->seller->name;
                            return $seller;
                    })
                    ->editColumn('customer',function($row){
                            $customer =  $row->customer->customer_id.'-'.$row->customer->customer_name;
                            return $customer;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        return view('seller::manage_chalan');
    }
    /*Sellers Dashboard Sales*/
    public function my_sales(Request $request)
    {
        $data = SellRequest::with('customer')->where('seller_id',auth('web')->user()->id)->get();

        if ($request->ajax()) {

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('voucher_no', function($row){
                        if($row->voucher_no){
                            return $row->voucher_no;
                        }else{
                            return "No Challan";
                        }
                    })
                    ->editColumn('del_date', function($row){
                        if( $row->del_date ){
                            return $row->del_date;
                        }
                        else{
                            return "Undelivered";
                        }
                    })
                    ->addColumn('action', function($row){

                    $btn = "";
                    $btn_view ='';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                    }
                    if($this->user->can('view_sales',app('Modules\Seller\Entities\Seller')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                    }
                    return $btn_view;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                ->rawColumns(['action','input','req_id'])
                ->make(true); exit;
        }
        return view('seller::my_sales');
    }
    /*Add Collection from Customer*/
    public function add_collection(Request $request)
    {
        if(!$this->user->can('add_collection',app('Modules\Seller\Entities\Seller'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
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

                    $coa = Accounts::where('HeadLevel',3)->where('HeadCode','Like','10201060'.'%')->latest()->first();
                    if ($coa!=NULL) {
                        $num = $coa['HeadCode'];
                        $int = (int)$num;
                        $headcode=$int+1;
                    }
                    else{
                        $headcode="102010600001";
                    }
                    $c_acc='CHK-'.$data['check_no'];
                    $created_by=$this->user->id;
                    $customer_coa['HeadCode'] = $headcode;
                    $customer_coa['HeadName'] = $c_acc;
                    $customer_coa['PHeadName'] = 'Check In Hand';
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
                    $n_data['is_credited'] = 0;
                    CheckList::add_check($n_data);
                }else{
                   $data['chk_head']= '';
                }

                if ($request->bank_id) {
                    $bank = Bank::where('bank_id',$data['bank_id'])->first();
                    $b_name = $bank->bank_name;
                    $c_id = Auth::user()->company_id;
                    $h_name = $b_name.'-'.$c_id;
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

            $customers = Customer::all();
            $companies = Company::where('parent_id','!=',0)->get();
            return view('seller::collection',compact('customers','companies'));exit;
        }
    }
    /*Print Invoice*/
    public function print_invoice($invoice_id)
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
        $title = 'BILL';
        $chalan = SellRequest::with('customer')->where('id',$invoice_id)->first();
        $del_amount = (int)$chalan->del_amount;
        $amount = convert_number_to_words($del_amount);
        $products = RequestProduct::with('products')->where('req_id',$chalan->id)->where("del_qnt","!=",0)->get();
        $company_info = Company::where('id',$chalan->company_id)->first();
        $sales_person_name = User::where("id",$chalan->seller_id)->select("name","user_id")->first();
        $pdf = PDF::loadView('seller::invoice',compact('chalan','amount','company_info','products','pdf_style','title','sales_person_name'));
        $pdf->setPaper('A4', 'potrait');
        $name = "INV".$chalan->voucher_no.".pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }

    public function bill_edit(Request $request, $id)
    {
        
        $cus_id = SellRequest::select(
            'customer_id',
            'req_id',
            'seller_id',
            'v_date',
            'del_amount',
            'is_approved',
            'remarks',
            'del_discount',
            'company_id',
            'sale_disc',
            'sale_discount_overwrite'
        )->with('seller')->where('id', $id)->first();

        $data = Customer::with('accounts')->where('id', $cus_id->customer_id)->first();
        $seller = User::select('name')->where('id', $cus_id->seller_id)->first();
        $cus_name = $data->customer_name;
        $seller_name = $seller->name;
        $v_date = $cus_id->v_date;
        $request_id = $cus_id->req_id;
        $req_id = $id;
        $remarks = $cus_id->remarks;
        $sale_disc = $cus_id->sale_disc;
        $sale_discount_overwrite = $cus_id->sale_discount_overwrite;
        $req_products = RequestProduct::with('products')->where('req_id', $id)->get();
        $total_amount = (int)$cus_id->del_amount;
        $dis_amount = (int)$cus_id->del_discount;
        $edate = date('Y-m-d');
        $company_info = Company::where('id', $cus_id->company_id)->first();
        return view('seller::bill_edit', compact('sale_disc', 'sale_discount_overwrite', 'dis_amount', 'req_id', 'req_products', 'cus_name', 'v_date', 'total_amount', 'seller_name', 'remarks', 'request_id', 'company_info', 'edate', 'cus_id'));

        return view('seller::bill_edit');
    }
    // Bill Edit End

    // Bill Update Start
    public function bill_update(Request $request, $id)
    {

        $product = RequestProduct::where('req_id', $id)->get();
        $data = $request;

        $total_amount = 0;
        $total_del_amount = 0;
        $total_discount_amount = 0;
        $del_discount_amount = 0;
        $sale_discount = (int)$data['sale_discount'] / 100;

        $amount = 0;
        $del_amount = 0;
    
        foreach ($data['product_id'] as $key => $value) {
            RequestProduct::where('req_id', $id)->where('product_id', $data['product_id'][$key]) ->update(array('unit_price' =>  (int)$data['unit_price'][$key]));

            $total_amount = $total_amount + ((int)$data['unit_price'][$key] * $data['qnty'][$key]);
            $total_del_amount = $total_del_amount + ((int)$data['unit_price'][$key] * $data['del_qnt'][$key]);
        }

        $amount = $total_amount - ($sale_discount * $total_amount);
        $del_amount = $total_del_amount - ($sale_discount * $total_del_amount);

        $total_discount_amount = $sale_discount * $total_amount;
        $del_discount_amount = $sale_discount * $total_del_amount;


       SellRequest::where('id', $id)->update(array(
        'amount' =>  $amount, 
        'discount' =>  $total_discount_amount, 
        'del_amount' => $del_amount, 
        'del_discount' => $del_discount_amount,
        'sale_disc' => (int)$data['sale_discount']
    ));

        return redirect('/seller/bill_edit/'.$id);
    }

    /*Undelivered Products from sales*/

    public function undelivered_sales(Request $request)
    {
        
        
        if( auth('web')->user()->role->id == 4 ){
            $sells = SellRequest::with('customer','seller')->where('seller_id',auth('web')->user()->id)->where('is_approved',1)->where('fully_delivered',0)->get();
        }
        else{
            $sells = SellRequest::with('customer','seller')->where('is_approved',1)->where('fully_delivered',0)->get();
        }
        
        $data = [];
        foreach ($sells as $key => $sale) {
            if ($sale->fully_delivered == 0) {
                $data[$key] = $sale;
            }
        }
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btn_view ='';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    }
                    if($this->user->can('view_sales',app('Modules\Seller\Entities\Seller')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    }
                    elseif( $this->user->role->id == 18 ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    }
                    return $btn;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->editColumn('price', function ($row) {
                        $data = $row->amount-$row->del_amount;
                        return $data;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        return view('seller::undelivered_sales');
    }
    
    
    public function rejected_sales(Request $request){
        if(!$this->user->can('browse',app('Modules\Seller\Entities\Seller'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        $data = SellRequest::with('customer','seller')->where("is_rejected",true)->get();
        $user_id = Auth::user()->id;

        $child_users = User::where('parent_id', $user_id)->select('id')->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btn_view ='';
                    $btn_print = '';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    }
                    // if($this->user->can('view_sales',app('Modules\Seller\Entities\Seller')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    // }
                    return $btn;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->editColumn('seller',function($row){
                        $seller_code = $row->seller->user_id;
                        $seller_name = $row->seller->name;
                        return  $seller_code.' - '.$seller_name;
                })
                ->rawColumns(['action','input','seller'])
                ->make(true); exit;
        }
        return view('seller::rejected.rejected_sales', compact('child_users'));
    }
    
    public function undelivered_details($req_id)
    {
        try{
            $products = RequestProduct::with('products')->where('req_id',$req_id)->where('qnty','!=','del_qnt')->get();
            $req_details = SellRequest::with('customer','seller')->where('id',$req_id)->first();
            return view('seller::undelivered_products',compact('products','req_id','req_details'));
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }
    
    //approve order funciton start
    public function undelivered_details_approve($id){
        self::voucher_no();
        $voucher_no_n = $this->voucher_no();


        try{
            $products = RequestProduct::with('products')->where('req_id',$id)->where('qnty','!=','del_qnt')->get();
            $req_details = SellRequest::where('id',$id)->first();

            // $data['req_id'] = generateRandomStr(8);
            $now = Carbon::now();
            $currentYear = $now->year;
            $currentMonth = $now->month;
            $currentDay = $now->day;
            $data['req_id'] = "BL-" . $currentYear . $currentMonth . $currentDay .'-'. explode("-",$voucher_no_n)[1];

            if ($this->user->isOfficeAdmin()) {
                $data['is_approved'] = 1;
                $data['approved_by'] = $this->user->id;

            }else{
                $data['is_approved'] = 1;
                $data['approved_by'] = $this->user->id;
            }
            $data['voucher_no']= $voucher_no_n;
            $data['v_date'] = date('Y-m-d');
            $data['company_id'] = $req_details->company_id;
            $data['seller_id'] = $req_details->seller_id;
            $data['customer_id'] = $req_details->customer_id;
            $data['pname'] = $req_details->pname;
            $data['receiver'] = $req_details->receiver;
            $data['dco_code'] = $req_details->dco_code;
            $data['phn_no'] = $req_details->phn_no;
            $data['remarks'] = 'Reorder of previos order No '. $id;
            $data['amount'] = $req_details->amount - $req_details->del_amount;
            $data['discount'] = $req_details->discount - $req_details->del_discount;
            $data['sale_disc'] = $req_details->sale_disc;

            $re_id = SellRequest::createRequest($data);
            SellRequest::where('id',$id)->update(['fully_delivered'=>1]);
            foreach ($products as $key => $product) {
                if( $product->qnty != $product->del_qnt ){
                    $n_data['product_id'] = $product->product_id;
                    $n_data['qnty'] = $product->qnty - $product->del_qnt;
                    $n_data['prod_disc'] = $product->prod_disc;
                    $n_data['req_id'] = $re_id;
                    RequestProduct::createProductReq($n_data);
                }
            }
            return back()->with('success','Approved');
        }catch(\Exception $e){
            return back()->with('success',$e->getMessage());
        }
    }
    //approve order funciton end

    public function re_order($req_id)
    {
        self::voucher_no();
        $voucher_no_n = $this->voucher_no();

        self::bill_no();
        $bill_no = $this->bill_no();


        try{
            $products = RequestProduct::with('products')->where('req_id',$req_id)->where('qnty','!=','del_qnt')->get();
            $req_details = SellRequest::where('id',$req_id)->first();
            // $data['req_id'] = generateRandomStr(8);
            $data['req_id'] = $bill_no;

            if ($this->user->isOfficeAdmin()) {
                $data['is_approved'] = 1;
                $data['approved_by'] = $this->user->id;
                // $data['voucher_no']= 'v-'.generateRandomStr(8);
                $data['voucher_no']= $voucher_no_n;

            }else{
                $data['seller_id'] = $this->user->id;
            }
            $data['v_date'] = date('Y-m-d');
            $data['company_id'] = $req_details->company_id;
            $data['seller_id'] = $req_details->seller_id;
            $data['customer_id'] = $req_details->customer_id;
            $data['pname'] = $req_details->pname;
            $data['receiver'] = $req_details->receiver;
            $data['dco_code'] = $req_details->dco_code;
            $data['phn_no'] = $req_details->phn_no;
            $data['remarks'] = 'Reorder of previos order No '. $req_id;
            $data['amount'] = $req_details->amount - $req_details->del_amount;
            $data['discount'] = $req_details->discount - $req_details->del_discount;
            $data['sale_disc'] = $req_details->sale_disc;


            $re_id = SellRequest::createRequest($data);
            SellRequest::where('id',$req_id)->update(['fully_delivered'=>1]);
            foreach ($products as $key => $product) {
                $n_data['product_id'] = $product->product_id;
                $n_data['qnty'] = $product->qnty - $product->del_qnt;
                $n_data['prod_disc'] = $product->prod_disc;
                $n_data['unit_price'] = $product->unit_price;
                $n_data['production_price'] = $product->production_price;
                $n_data['req_id'] = $re_id;
                RequestProduct::createProductReq($n_data);
            }
            return response()->json(['status'=>'success'], 200);
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);
        }
        return response()->json(['status'=>'success']);
    }

    public function direct_sale(Request $request)
    {
        self::voucher_no();
        $voucher_no_n = $this->voucher_no();

        self::bill_no();
        $bill_no = $this->bill_no();

        if (!$this->user->isOfficeAdmin()) {
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                // $data['req_id'] = generateRandomStr(8);
                $data['req_id'] = $bill_no;
                $customer_id = $data['customer_id'];
                $seller_get = Customer::where('id',$customer_id)->first();
                $data['seller_id'] = $seller_get->seller_id;
                $data['del_date'] = $data['v_date'];
                $data['del_amount'] = $data['amount'];
                $data['del_discount'] = $data['discount'];
                $data['is_approved'] = 1;
                $data['is_delivered'] = 1;
                $data['approved_by'] = $this->user->id;
                $data['fully_delivered'] = 1;
                // $v_no = 'v-'.generateRandomStr(8);
                $data['voucher_no']= $voucher_no_n;
                $re_id = SellRequest::createRequest($data);

                return $data;
                foreach ($data['product_id'] as $key => $value) {
                    //Product Request//
                    $n_data['product_id'] = $data['product_id'][$key];
                    $n_data['qnty'] = $data['qnty'][$key];
                    $n_data['del_qnt'] = $data['qnty'][$key];
                    $n_data['prod_disc'] = $data['prod_disc'][$key];
                    $n_data['req_id'] = $re_id;
                    RequestProduct::createProductReq($n_data);
                    //Warehouse Inserts to track//
                    $in_data['product_id'] = $data['product_id'][$key];
                    $in_data['out_qnt'] = $data['qnty'][$key];
                    $in_data['warehouse_id'] = $data['warehouse_id'];
                    $in_data['company_id']=$data['company_id'];
                    $in_data['v_date']=$data['v_date'];
                    $in_data['chalan_no']=$v_no;
                    $in_data['created_by']=$this->user->id;
                    $in_data['del_date'] = date('Y-m-d');
                    WarehouseInserts::insertware_product($in_data);
                    //Update the stock//
                    $product_qnt= WarehouseProducts::select('sell_q','id')->where('warehouse_id',$data['warehouse_id'])->where('product_id',$data['product_id'][$key])->first();
                    $id = $product_qnt->id;
                    $out = $data['qnty'][$key];
                    $new_q= $product_qnt->sell_q + $out;
                    WarehouseProducts::where('id',$id)->update(['sell_q'=>$new_q]);
                }

                //Inventory Credit And Customer Debit//
                $coaid = Accounts::select('HeadCode')->where('customer_id',$data['customer_id'])->first();
                $cus_coa = $coaid->HeadCode;
                $IsPosted=1;
                $IsAppove=1;
                $created_by = $this->user->id;
                $updated_by = $this->user->id;
                //Customer debit for Product Value
                $customerdebit = array(
                    'VNo'            =>  $v_no,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $data['v_date'],
                    'COAID'          =>  $cus_coa,
                    'Narration'      =>  'Customer debit For Invoice No -  '.$re_id.' Customer '.$seller_get->customer_name,
                    'Debit'          =>  $data['amount'],
                    'Credit'         =>  0,
                    'IsPosted'       =>  $IsPosted,
                    'created_by'     =>  $created_by,
                    'updated_by'     =>  $updated_by,
                    'company_id'     =>  $data['company_id'],
                    'IsAppove'       =>  $IsAppove
                );
                Transactions:: createTransaction($customerdebit);
                ///Inventory credit
                $coscr = array(
                    'VNo'            =>  $v_no,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $data['v_date'],
                    'COAID'          =>  10107,
                    'Narration'      =>  'Inventory credit For Invoice No'.$re_id,
                    'Debit'          =>  0,
                    'Credit'         =>  $data['amount'],//Deliver Amount
                    'IsPosted'       =>  $IsPosted,
                    'created_by'     =>  $created_by,
                    'updated_by'     =>  $updated_by,
                    'company_id'     =>  $data['company_id'],
                    'IsAppove'       =>  $IsAppove
                );
                Transactions:: createTransaction($coscr);
                return response()->json(['status'=>'success','data'=>$v_no], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $customers = Customer::get();
            $products = Product::all();
            $warehouses = Warehouse::all();
            $companies = Company::where('parent_id','!=',0)->get();
            return view('seller::direct_sale',compact('customers','products','companies','warehouses'));exit;
        }
    }

    public function sales_bydate(Request $request)
    {
        $data = $request->all();
        $sales = SellRequest::with('customer','seller')->where('is_delivered',1)->whereBetween('del_date', [$data['from'],$data['to']])->get();
        return view('seller::sale_bydate',compact('sales'));exit;
    }

    // Update or Overwrite Sale Discount by Admin
    public function updateSaleDiscount(Request $request){
        if($request->ajax()){
            $new_sale_discount = $request->get('new_sale_discount');
            $updated_by = $this->user->id;
            $id = $request->id;
            $amount = $request->amount;
            $discount = $request->discount;

            SellRequest::where('id',$id)->update(['sale_discount_overwrite'=>$new_sale_discount, 'update_by'=>$updated_by, 'amount' => $amount, 'discount' => $discount]);

            return response()->json(['status' => 'success', 'message' => 'Sale Discount Successfully Updated'],200);
        }
    }

    //Update Due Amount
    public function updateDueAmount(Request $request){
        if($request->ajax()){
            $due_amount = $request->get('due_amount');
            $id = $request->get('id');

            SellRequest::where('id', $id)->update(['due_amount' => $due_amount]);
            return response()->json(['status' => 'success', 'message' => 'Due Amount Saved'],200);
        }
    }



    // All Undelivered Products Report Start
    public function all_undelivered_products(){
        $undelivered_products = RequestProduct::with('products')
                                                ->select(DB::raw("product_id, (sum(qnty) - sum(del_qnt)) as undelivered_product"))
                                                ->where('qnty' ,'<>', 'del_qnt')
                                                ->groupBy('product_id')
                                                ->get();
        $product = Product::all();
        return view('seller::total_undelivered_product_report', compact('undelivered_products', 'product'));
    }
    // All Undelivered Products Report End

    // Print Undelivered Products Start
    public function undelivered_product_print(){
        $undelivered_products = RequestProduct::with('products')
                                                ->select(DB::raw("product_id, (sum(qnty) - sum(del_qnt)) as undelivered_product"))
                                                ->where('qnty' ,'<>', 'del_qnt')
                                                ->groupBy('product_id')
                                                ->get();
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
    $title = 'Undelivered Product Report';
    $company_info = Company::where('id',$this->user->company_id)->first();
    $pdf = PDF::loadView('seller::undelivered_product_print',compact('undelivered_products', 'company_info' ,'pdf_style','title'));
    $pdf->setPaper('A4', 'potrait');
    $name = "UndeliveredProductReport.pdf";
    return $pdf->stream($name, array("Attachment" => false));

    }
    // Print Undelivered Products End

    // Undelivered Product Search Start
    public function undelivered_product_search(Request $request){
        $product_id = $request->product_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if($product_id){
            $undelivered_products = RequestProduct::with('products')
                                                ->select(DB::raw("product_id, (sum(qnty) - sum(del_qnt)) as undelivered_product"))
                                                ->where('qnty' ,'<>', 'del_qnt')
                                                ->where('product_id', $product_id)
                                                ->groupBy('product_id')
                                                ->get();
        }elseif($from_date && $to_date){
            $undelivered_products = RequestProduct::with('products')
                                                ->select(DB::raw("product_id, (sum(qnty) - sum(del_qnt)) as undelivered_product"))
                                                ->where('qnty' ,'<>', 'del_qnt')
                                                ->where('created_at','>=', $from_date)
                                                ->where('created_at', '<=',  $to_date)
                                                ->groupBy('product_id')
                                                ->get();

        }else{
            $undelivered_products = RequestProduct::with('products')
                                                ->select(DB::raw("product_id, (sum(qnty) - sum(del_qnt)) as undelivered_product"))
                                                ->where('qnty' ,'<>', 'del_qnt')
                                                ->where('product_id', $product_id)
                                                ->where('created_at','>=', $from_date)
                                                ->where('created_at', '<=',  $to_date)
                                                ->groupBy('product_id')
                                                ->get();
        }
        return view('seller::undeliveredProductReportView', compact('undelivered_products'));
    }
    // Undelivered Product Search End

    // Child Sell Requisition Show Start
    public function child_users_requisition(Request $request){
        $user_id = Auth::user()->id;
        $child_users = User::where('parent_id', $user_id)->select('id')->get();

        $data = SellRequest::with('customer','seller')->where('is_approved',0)
                            ->whereIn('seller_id', $child_users)->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btn_view ='';
                    $btn_print = '';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    }
                    if($this->user->can('view_sales',app('Modules\Seller\Entities\Seller')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
                        $btn = $btn_view;
                    }
                    return $btn;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                    ->editColumn('seller',function($row){
                        $seller_code = $row->seller->user_id;
                        $seller_name = $row->seller->name;
                        return  $seller_code.' - '.$seller_name;
                })
                ->rawColumns(['action','input','seller'])
                ->make(true); exit;
        }

        return view('seller::edit-sale-discount');
    }
    // Child Sell Requisition Show End



    //change password page start
    public function change_password_page($id){
        return view('seller::change_password.index', compact('id'));
    }
    //change password page end

    //change password start
    public function change_password($id, Request $request){
        $request->validate([
            "old_password" => "required",
            "password" => "required|confirmed|min:6",
        ]);

        $user = User::find($id);
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                $request->session()->flash("success","Password Updated");
                return redirect()->route('seller.change.password.page', $id);
            }
        } else {
            $request->session()->flash("error","Password Not Match");
            return redirect()->route('seller.change.password.page', $id);

        }
    }
    //change password end

    // rejected_sales method start
   public function rejected_status($id){
     
    if($this->user->isOfficeAdmin()){

        $sell_request_id = SellRequest::where('id', $id)->select('id')->first();

        if($sell_request_id){
            $reject_request = SellRequest::find($id);
            $reject_request->is_rejected = true;
            $reject_request->save();   
            return back()->with('success','Success message');
        }else{
            return back()->with('error','Error message');
        }
        
        

        }
    }
    // rejected_sales method end

   
    public function challan_update(){
        $sellRequest = SellRequest::where('challan_no', NULL)->get();
        $total = 0;
        foreach($sellRequest as $row){
            $total = $total + 1;
            SellRequest::where('voucher_no', $row->voucher_no)->update(array(
                'challan_no' =>  $row->voucher_no
            ));
        }

        echo "Total=".$total;
    }




}
