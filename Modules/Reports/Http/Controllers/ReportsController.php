<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Customer\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\WarehouseProducts;
use Modules\Seller\Entities\SellRequest;
use Modules\Seller\Entities\RequestProduct;
use Modules\Accounts\Entities\Accounts;
use Modules\Accounts\Entities\Transactions;
use Modules\Company\Entities\Company;
use App\User;
use DB;
use DataTables;
use Carbon\Carbon;
use PDF;
class ReportsController extends BaseController
{

    public function product_wise(Request $request)
    {
        $data = WarehouseProducts::with('warehouse','products')->get();
        $edate = date('Y-m-d');
        $warehouses = Warehouse::get();
        $company_info = Company::where('id',$this->user->company_id)->first();
        if ($request->ajax()) {          
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('input',function($row){
                    $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                    return $input;
                })
                ->editColumn('p_name',function($row){
                    $p_name = $row->products[0]->product_id.' '.$row->products[0]->product_name;
                    return $p_name;
                })
                ->editColumn('w_name',function($row){
                    $w_name = $row->warehouse[0]->name;
                    return $w_name;
                })
                ->rawColumns(['input','w_name','p_name'])
                ->make(true); exit;
        }
        return view('reports::product_wise',compact('edate','warehouses','company_info'));
    }

    public function sales_person_wise()
    {
        $sale_details = array();
        $receive_details = array();
        $sellers = User::with('customers')->where('role_id',4)->get();
        foreach ($sellers as $key => $seller) {
            $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id')->groupBy('company_id')->where('seller_id',$seller->id)->get();
            $coa_heads = array();
            foreach ($seller->customers as $customer) {
                $coa= Accounts::select('HeadCode')->where('customer_id',$customer->id)->first();
                $coa_id = $coa->HeadCode;
                array_push($coa_heads, $coa_id);
            }
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->get();
            if(count($cu_rec) > 0){
                foreach ($cu_rec as $rec_value) {
                    $receive_details[] = array(
                        'amount' => $rec_value->sum_receive,
                        'company_id' => $rec_value->company_id,
                    );
                }
                $sellers[$key]['customer_receive'] = $receive_details;
            }else{
                $sellers[$key]['customer_receive'] = array();
            }
            if(count($sells) > 0){
                foreach ($sells as $sell_value) {
                    $sale_details[] = array(
                        'amount' => $sell_value->sum_sales,
                        'company_id' => $sell_value->company_id,
                    );
                }
                $sellers[$key]['sales_details'] = $sale_details;
            }else{
                $sellers[$key]['sales_details'] = array();
            }
            $receive_details = array();
            $sale_details = array();
        }
        $companies = Company::where('parent_id','!=',0)->get(); 
        return view('reports::sales_person',compact('companies','sellers'));
    }
    public function sell_report_by_seller(Request $request)
    {
        $data = $request->all();
        //dd($data);exit();
        $seller_id = $data['seller_id'];
        $company_id = $data['company_id'];
        $sdate = $data['from'];
        $edate = $data['to'];
        $sale_details = array();
        $receive_details = array();
        $seller = User::with('customers')->where('id',$seller_id)->first();

        if ($request->company_id && $request->seller_id && $request->from && $request->to) {
            $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id')->groupBy('company_id')->whereBetween('v_date',[$sdate,$edate])->where('seller_id',$seller_id)->where('company_id',$company_id)->get();
        }elseif($request->company_id && $request->seller_id){
            $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id')->groupBy('company_id')->where('seller_id',$seller_id)->where('company_id',$company_id)->get();
        }elseif($request->seller_id && $request->from && $request->to){
            $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id')->groupBy('company_id')->where('seller_id',$seller_id)->whereBetween('v_date',[$sdate,$edate])->get();
        }elseif($request->seller_id){
            $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id')->groupBy('company_id')->where('seller_id',$seller_id)->get();
        }
        if(count($sells) > 0){
            foreach ($sells as $sell_value) {
                $sale_details[] = array(
                    'amount' => $sell_value->sum_sales,
                    'company_id' => $sell_value->company_id,
                );
            }
            $seller['sales_details'] = $sale_details;
        }else{
            $seller['sales_details'] = array();
        }
        $coa_heads = array();
        foreach ($seller->customers as $customer) {
            $coa= Accounts::select('HeadCode')->where('customer_id',$customer->id)->first();
            $coa_id = $coa->HeadCode;
            array_push($coa_heads, $coa_id);
        }
        if($request->company_id && $request->from && $request->to) {
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->whereBetween('VDate',[$sdate,$edate])->where('company_id',$company_id)->get();
        }elseif($request->from && $request->to){
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->whereBetween('VDate',[$sdate,$edate])->get();
        }elseif($request->company_id){
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->where('company_id',$company_id)->get();
        }else{
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->get();
        }
        if(count($cu_rec) > 0){
            foreach ($cu_rec as $rec_value) {
                $receive_details[] = array(
                    'amount' => $rec_value->sum_receive,
                    'company_id' => $rec_value->company_id,
                );
            }
            $seller['customer_receive'] = $receive_details;
        }else{
            $seller['customer_receive'] = array();
        }
        if ($request->company_id) {
            $companies = Company::where('id',$company_id)->get(); 
        }else{
            $companies = Company::where('parent_id','!=',0)->get(); 
        }
        
        return view('reports::report_person_wise',compact('companies','seller','sdate','edate'));
    }

    public function report_seller_details($id, $company_id){
        
        $seller = User::find($id);
        $sellers = User::with('customers')->where('role_id',4)->get();
        $companies = Company::where('parent_id','!=',0)->get(); 
        if($company_id == 0){
            $company = "All";
            $report_details = SellRequest::with('customer','company')->where('seller_id', $id)->orderBy('v_date', 'DESC')->get();
        }else{
            $company_info = Company::find($company_id);
            $company = $company_info->name;
            $report_details = SellRequest::with('customer','company')->where('seller_id', $id)->where('company_id', $company_id)->orderBy('v_date', 'DESC')->get();
        }

        return view('reports::seller_report_details.index', compact('report_details','seller','company','sellers', 'companies','company_id'));
    }

    public function sell_report_by_seller_details_search(Request $request){
        $seller_id = $request->seller_id;
        $company_id = $request->company_id;
        $from = $request->from;
        $to = $request->to;

        if($request->from && $request->to){
            if($company_id == 0){
                $report_details = SellRequest::with('customer','company')
                                            ->where('seller_id', $seller_id)
                                            ->orderBy('v_date', 'DESC')
                                            ->whereBetween('v_date', [$from, $to])
                                            ->get();
            }else{
                $report_details = SellRequest::with('customer', 'company')
                                            ->where('seller_id', $seller_id)
                                            ->where('company_id', $company_id)
                                            ->orderBy('v_date', 'DESC')
                                            ->whereBetween('v_date', [$from, $to])
                                            ->get();

            }
        }else{
            if($company_id == 0){
                $report_details = SellRequest::with('customer','company')
                                            ->where('seller_id', $seller_id)
                                            ->orderBy('v_date', 'DESC')
                                            ->get();
            }else{
                $report_details = SellRequest::with('customer', 'company')
                                            ->where('seller_id', $seller_id)
                                            ->where('company_id', $company_id)
                                            ->orderBy('v_date', 'DESC')
                                            ->get();

            }
        }

        return view('reports::seller_report_details.seller_report_by_search', compact('report_details'));
    }

    public function company_wise(Request $request)
    {
        $sale_details = array();
        $receive_details = array();
        $sellers = User::with('customers')->where('role_id',4)->paginate(15);
        foreach ($sellers as $key => $seller) {
            $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id')->groupBy('company_id')->where('seller_id',$seller->id)->get();
            $coa_heads = array();
            foreach ($seller->customers as $customer) {
                $coa= Accounts::select('HeadCode')->where('customer_id',$customer->id)->first();
                $coa_id = $coa->HeadCode;
                array_push($coa_heads, $coa_id);
            }
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->get();
            if(count($cu_rec) > 0){
                foreach ($cu_rec as $rec_value) {
                    $receive_details[] = array(
                        'amount' => $rec_value->sum_receive,
                        'company_id' => $rec_value->company_id,
                    );
                }
                $sellers[$key]['customer_receive'] = $receive_details;
            }else{
                $sellers[$key]['customer_receive'] = array();
            }
            if(count($sells) > 0){
                foreach ($sells as $sell_value) {
                    $sale_details[] = array(
                        'amount' => $sell_value->sum_sales,
                        'company_id' => $sell_value->company_id,
                    );
                }
                $sellers[$key]['sales_details'] = $sale_details;
            }else{
                $sellers[$key]['sales_details'] = array();
            }
            $receive_details = array();
            $sale_details = array();
        }
        $companies = Company::where('parent_id','!=',0)->get(); 
        return view('reports::all_company_report',compact('companies','sellers'));
    }

    public function sell_report_by_company(Request $request)
    {
        $data = $request->all();
        $company_id = $data['company_id'];
        $seller_id = $data['seller_id'];
        $sdate = $data['from'];
        $edate = $data['to'];
        $sale_details = array();
        $receive_details = array();
        if ($request->seller_id) {
            $sellers = User::with('customers')->where('role_id',4)->where('id',$data['seller_id'])->get();
        }else{
            $sellers = User::with('customers')->where('role_id',4)->get();
        }
        foreach ($sellers as $key => $seller) {
            if ($sdate !== null && $edate !== null) {
                $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'))->whereBetween('v_date',[$sdate,$edate])->where('company_id',$company_id)->where('seller_id',$seller->id)->get();
            }else{
                $sells=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'))->where('company_id',$company_id)->where('seller_id',$seller->id)->get(); 
            }
            
            $coa_heads = array();
            foreach ($seller->customers as $customer) {
                $coa= Accounts::select('HeadCode')->where('customer_id',$customer->id)->first();
                $coa_id = $coa->HeadCode;
                array_push($coa_heads, $coa_id);
            }
            if ($sdate !== null && $edate !== null) {
                $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'))->where('Vtype','CR')->where('company_id',$company_id)->wherein('COAID',$coa_heads)->whereBetween('VDate',[$sdate,$edate])->get();
            }else{
                $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'))->where('Vtype','CR')->wherein('COAID',$coa_heads)->where('company_id',$company_id)->get();
            }
            if(count($cu_rec) > 0){
                foreach ($cu_rec as $rec_value) {
                    $receive_details[] = array(
                        'amount' => $rec_value->sum_receive
                    );
                }
                $sellers[$key]['customer_receive'] = $receive_details;
            }else{
                $sellers[$key]['customer_receive'] = array();
            }
            if(count($sells) > 0){
                foreach ($sells as $sell_value) {
                    $sale_details[] = array(
                        'amount' => $sell_value->sum_sales
                    );
                }
                $sellers[$key]['sales_details'] = $sale_details;
            }else{
                $sellers[$key]['sales_details'] = array();
            }
            $receive_details = array();
            $sale_details = array();
        }
        $company_info = Company::where('id',$company_id)->first();
        return view('reports::report_company_wise',compact('company_info','sellers'));     
    }
    public function customer_wise(Request $request)
    {
        $sale_details = array();
        $receive_details = array();
        $customers = Customer::all();
        foreach ($customers as $key => $customer) {
            $buys=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id',DB::raw('SUM(del_discount) as sum_discount'))->groupBy('company_id')->where('customer_id',$customer->id)->get();
            if(count($buys) > 0){
                foreach ($buys as $sell_value) {
                    $sale_details[] = array(
                        'amount' => $sell_value->sum_sales,
                        'del_discount' => $sell_value->sum_discount,
                        'company_id' => $sell_value->company_id,
                    );
                }
                $customers[$key]['sales_details'] = $sale_details;
            }else{
                $customers[$key]['sales_details'] = array();
            }
            $coa_heads = array();
            $coa= Accounts::select('HeadCode')->where('customer_id',$customer->id)->first();
            $coa_id = $coa->HeadCode;
            array_push($coa_heads, $coa_id);
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->get();
            if(count($cu_rec) > 0){
                foreach ($cu_rec as $rec_value) {
                    $receive_details[] = array(
                        'amount' => $rec_value->sum_receive,
                        'company_id' => $rec_value->company_id,
                    );
                }
                $customers[$key]['customer_receive'] = $receive_details;
            }else{
                $customers[$key]['customer_receive'] = array();
            }
            $receive_details = array();
            $sale_details = array();
        }
        $companies = Company::where('parent_id','!=',0)->get(); 
        return view('reports::customerwise_report',compact('companies','customers'));
    }


    // Print Customer/ Party Wise All Company Report
    public function customer_wise_print(Request $request)
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
        $title = 'Party Wise Reporting';

        $sale_details = array();
        $receive_details = array();
        // $customers = Customer::paginate(15);
        $customers = Customer::all();

        foreach ($customers as $key => $customer) {
            $buys=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id',DB::raw('SUM(del_discount) as sum_discount'))->groupBy('company_id')->where('customer_id',$customer->id)->get();
            if(count($buys) > 0){
                foreach ($buys as $sell_value) {
                    $sale_details[] = array(
                        'amount' => $sell_value->sum_sales,
                        'del_discount' => $sell_value->sum_discount,
                        'company_id' => $sell_value->company_id,
                    );
                }
                $customers[$key]['sales_details'] = $sale_details;
            }else{
                $customers[$key]['sales_details'] = array();
            }
            $coa_heads = array();
            $coa= Accounts::select('HeadCode')->where('customer_id',$customer->id)->first();
            $coa_id = $coa->HeadCode;
            array_push($coa_heads, $coa_id);
            $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->get();
            if(count($cu_rec) > 0){
                foreach ($cu_rec as $rec_value) {
                    $receive_details[] = array(
                        'amount' => $rec_value->sum_receive,
                        'company_id' => $rec_value->company_id,
                    );
                }
                $customers[$key]['customer_receive'] = $receive_details;
            }else{
                $customers[$key]['customer_receive'] = array();
            }
            $receive_details = array();
            $sale_details = array();
        }
        $company_info = Company::where('id',$this->user->company_id)->first();
        $companies = Company::where('parent_id','!=',0)->get(); 
        $pdf = PDF::loadView('reports::customerwise_report_print',compact('company_info','customers','companies','pdf_style','title'));
        $pdf->setPaper('A4', 'landscape');
        $name = "Party_Wise_Reporting.pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }
    
    public function sell_report_by_customer(Request $request)
    {
        $data = $request->all();
        $company_id = $data['company_id'];
        $customer_id = $data['customer_id'];
        $sdate = $data['from'];
        $edate = $data['to'];
        $sale_details = array();
        $receive_details = array();
        if ($request->customer_id) {
            $customers= Customer::where('id',$data['customer_id'])->get();
        }else{
            $customers = Customer::paginate(15);
        }
        foreach($customers as $key => $customer){
            if ($sdate !== null && $edate !== null && $request->company_id) {
                $buys=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id',DB::raw('SUM(del_discount) as sum_discount'))->groupBy('company_id')->whereBetween('v_date',[$sdate,$edate])->where('customer_id',$customer->id)->where('company_id',$company_id)->get();
            }elseif($request->edate && $request->sdate){
                $buys=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id',DB::raw('SUM(del_discount) as sum_discount'))->groupBy('company_id')->whereBetween('v_date',[$sdate,$edate])->where('customer_id',$customer->id)->get();
            }else{
                $buys=  DB::table('sell_requests')->select(DB::raw('SUM(del_amount) as sum_sales'),'company_id',DB::raw('SUM(del_discount) as sum_discount'))->groupBy('company_id')->where('customer_id',$customer->id)->get();
            }
            if(count($buys) > 0){
                foreach ($buys as $sell_value) {
                    $sale_details[] = array(
                        'amount' => $sell_value->sum_sales,
                        'del_discount' => $sell_value->sum_discount,
                        'company_id' => $sell_value->company_id,
                    );
                }
                $customers[$key]['sales_details'] = $sale_details;
            }else{
                $customers[$key]['sales_details'] = array();
            }
            $coa_heads = array();
            $coa= Accounts::select('HeadCode')->where('customer_id',$customer->id)->first();
            $coa_id = $coa->HeadCode;
            array_push($coa_heads, $coa_id);
            if ($sdate !== null && $edate !== null && $request->company_id) {
                $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->where('company_id',$company_id)->whereBetween('VDate',[$sdate,$edate])->get();
            }elseif($request->edate && $request->sdate){
                $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->whereBetween('VDate',[$sdate,$edate])->get();
            }else{
               $cu_rec= Transactions::select(DB::raw('SUM(Credit) as sum_receive'),'company_id')->where('Vtype','CR')->wherein('COAID',$coa_heads)->groupBy('company_id')->get(); 
            }
            if(count($cu_rec) > 0){
                foreach ($cu_rec as $rec_value) {
                    $receive_details[] = array(
                        'amount' => $rec_value->sum_receive,
                        'company_id' => $rec_value->company_id,
                    );
                }
                $customers[$key]['customer_receive'] = $receive_details;
            }else{
                $customers[$key]['customer_receive'] = array();
            }
            $receive_details = array();
            $sale_details = array();
        }
        if ($request->company_id) {
            $companies = Company::where('id',$data['company_id'])->get(); 
        }else{
            $companies = Company::where('parent_id','!=',0)->get();
        } 
        // return $coa_id;
        return view('reports::report_customer_wise',compact('companies','customers','coa_id','company_id', 'sdate', 'edate'));
    }


    // Customer or Party Ladger Details 
    public function report_customer_details(Request $request, $customer_id, $company_id, $from=null, $to=null){
        $cus_coa = Accounts::select('HeadCode')->where('customer_id', $customer_id)->first();
        $customer = Customer::where('id', $customer_id)->first();
        if($company_id == 0){
            if($from && $to){
                $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)
                                                            ->whereBetween('VDate', [$from, $to])    
                                                            ->get();

            }else{
                $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->get();
            }
            $company = 'All';
        }else{
            if($from && $to){
                $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)
                                                            ->whereBetween('VDate', [$from, $to])    
                                                            ->get();

            }else{
                $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->get();
            }
            // $transactions = Transactions::with('company')->where('COAID',$cus_coa->HeadCode)->where('company_id', $company_id)->get();
            $company_info = Company::find($company_id);
            $company = $company_info->name;
        }
        
        return view('reports::customer_report_details.index', compact('customer', 'transactions','company'));
    }



    public function product_idby($pr_id=null,$wr_id=null)
    {
        if ($wr_id == 0) {
            $product_id = Product::select('id')->where('product_id',$pr_id)->first();
            if ($product_id !== null) {
                $p_id = $product_id->id;
                $product = WarehouseProducts::with('products','warehouse')->where('product_id',$p_id)->get();
            }else{
                $product = null;
            }
        }else{
            $product_id = Product::select('id')->where('product_id',$pr_id)->first();
            if ($product_id !== null) {
                $p_id = $product_id->id;
                $product = WarehouseProducts::with('products','warehouse')->where('product_id',$p_id)->where('warehouse_id',$wr_id)->get();
            }else{
                $product = null;
            }
        }
        return view('reports::productby_report',compact('product'));
    }
    
    
    
    
    //daily sales report index route start
    public function daily_sales_report(){


        $sell_requests = SellRequest::all();

        $sell_requests = SellRequest::with('customer','seller')->where("is_delivered",true)->where("is_rejected",false)->where("del_date",Carbon::now()->toDateString())
        ->select("id","voucher_no","challan_no","v_date","req_id","del_date","amount","del_discount","seller_id","remarks","sale_disc","customer_id")
        ->orderBy('challan_no','asc')
        ->paginate(10);
        
        return view('reports::daily_sales_report.index',compact('sell_requests'));
    }
    //daily sales report index route end


    //print daily sales report function start
    public function print_daily_sales_report(){
        
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
        
        $sell_requests = SellRequest::with('customer','seller')->where("is_delivered",true)->where("is_rejected",false)->where("del_date",Carbon::now()->toDateString())
        ->select("id","voucher_no","challan_no","v_date","req_id","del_date","amount","del_discount","seller_id","remarks","sale_disc","customer_id")
        ->orderBy('challan_no','asc')
        ->get();
        $title = 'DAILY SALES REPORT';
        $company_info = Company::where('id',8)->first();
        $today = Carbon::now()->toDateString();
        
        $pdf = PDF::loadView('reports::daily_sales_report.pdf',compact('sell_requests','company_info','title','pdf_style'));
        $pdf->setPaper('legal', 'landscape');
        $name = "Daily_Sales_Report_".$today.".pdf";
        return $pdf->stream($name, array("Attachment" => false));
        
        return view('reports::daily_sales_report.pdf',compact('sell_requests','company_info','title'));
    }
    //print daily sales report function end
    
    
    //search daily sales report function start
    public function daily_sales_report_search(Request $request){
        $date = $request->date;
        $sell_requests = SellRequest::where("del_date",$date)->with('customer','seller')->where("is_delivered",true)->where("is_rejected",false)
        ->select("id","voucher_no","challan_no","v_date","req_id","del_date","amount","del_discount","seller_id","remarks","sale_disc","customer_id")
        ->orderBy('challan_no','asc')
        ->get();
        return view('reports::daily_sales_report.search',compact('sell_requests','date'));
    }
    //search daily sales report function end
    
    
    
    //search daily sales report pdf function start
    public function daily_sales_report_search_pdf($date){
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
        
        $sell_requests = SellRequest::with('customer','seller')->where("is_delivered",true)->where("is_rejected",false)->where("del_date",$date)
        ->select("id","voucher_no","challan_no","v_date","req_id","del_date","amount","del_discount","seller_id","remarks","sale_disc","customer_id")
        ->orderBy('challan_no','asc')
        ->get();
        $title = 'DAILY SALES REPORT';
        $company_info = Company::where('id',8)->first();
        
        $pdf = PDF::loadView('reports::daily_sales_report.pdf_search',compact('sell_requests','company_info','title','pdf_style'));
        $pdf->setPaper('legal', 'landscape');
        $name = "Daily_Sales_Report_".$date.".pdf";
        return $pdf->stream($name, array("Attachment" => false));
        
    }
    //search daily sales report pdf function end
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
