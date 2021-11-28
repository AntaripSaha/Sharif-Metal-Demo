<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Unit;
use Modules\Product\Entities\Category;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Product\Entities\ProductSet;
use Modules\Supplier\Entities\Supplier;
use App\User;
use Carbon\Carbon;
use DB;
use DataTables;
use Modules\Company\Entities\Company;
use PDF;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if(!$this->user->can('browse',app('Modules\Product\Entities\Product'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        $data = Product::with('category')->get();
        if ($request->ajax()) {          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btnDel ="";
                    $btn_view ='';
                    $btn_edit ='';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                        $btn_edit ='<a class="mr-2 btn btn-info btn-sm cp edit-tr" id="edit-tr-'.$row->id.'">Edit</a>';
                    }
                    if($this->user->can('view_product',app('Modules\Product\Entities\Product')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                    }
                    if($this->user->can('edit_product',app('Modules\Product\Entities\Product')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> Edit</a>'; 
                    }
                    return $btn_view.$btn_edit;   
                    })
                    ->editColumn('product_name',function($row){
                        $product_name = Str::limit($row->product_name, 20, '...');
                        $code = $row->product_id;
                        $prod = $code.' - '.$product_name;
                        return $prod;
                    })
                    ->editColumn('head_code', function($row){
                        if($row->head_code){
                            return $row->head_code;
                        }else{
                            return '-';
                        }
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        return view('product::product_index');
    }

    public function add_product(Request $request)
    {
        if(!$this->user->can('add_product',app('Modules\Product\Entities\Product'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        if($request->isMethod('post')){
            $date = Carbon::now()->toDateString();
            // return $date;
            try{  
                $data=$request->all();
                if($request->company_id == 0){
                    $data['company_id'] = null;
                }else{
                    $data['company_id'] = $request->company_id;
                }
                $data['date'] = $date;
                $combo_ids = '';
                // If Product Have Head Start
                if($request->is_head == 1){
                    foreach($request->head_code as $key => $value){
                        $data['head_code'] = $request->head_code[$key];
                        $data['price'] = $request->head_price[$key];
                        Product::createProduct($data);
                    }
                    // return $data;exit();
                }else{
                    if ($request->is_set == 1) {
                        $s_id = generateRandomStr(8);
                        foreach ($request->set_prod as $key => $value) {
                            $n_data['set_id'] = $s_id;
                            $n_data['product_id'] = $value;
                            $p_code = Product::find($value);
                            if ($combo_ids == null) {
                                $combo_ids = $p_code->product_id;
                            }else{
                                $combo_ids = $combo_ids.'_'.$p_code->product_id;
                            }
                            ProductSet::createSet($n_data);
                        }
                        $data['combo_ids'] = $combo_ids;
                        $data['set_id'] = $s_id;
                    }
                    if (array_key_exists('image', $data)) {
                        $img = img_process($data['image'],'app/public/uploads');
                        $data['image'] = $img;
                    }
                    Product::createProduct($data);
                }
                // If Product Have Head End
                // if ($request->is_set == 1) {
                //     $s_id = generateRandomStr(8);
                //     foreach ($request->set_prod as $key => $value) {
                //         $n_data['set_id'] = $s_id;
                //         $n_data['product_id'] = $value;
                //         $p_code = Product::find($value);
                //         if ($combo_ids == null) {
                //             $combo_ids = $p_code->product_id;
                //         }else{
                //             $combo_ids = $combo_ids.'_'.$p_code->product_id;
                //         }
                //         ProductSet::createSet($n_data);
                //     }
                //     $data['combo_ids'] = $combo_ids;
                //     $data['set_id'] = $s_id;
                // }
                // if (array_key_exists('image', $data)) {
                //     $img = img_process($data['image'],'app/public/uploads');
                //     $data['image'] = $img;
                // }

                // Product::createProduct($data);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            $warehouses = Warehouse::get();
            $suppliers = Supplier::get();
            $categories = Category::get();
            $units = Unit::get();
            $products = Product::select('id','product_id')->where('is_set',0)->get();
            $companies = Company::where('parent_id', 1)->get();
            return view('product::add_product',compact('warehouses','categories','units','products','suppliers', 'companies'));exit;
        }
    }

    public function view_product($id)
    {
        if(!$this->user->can('view_product',app('Modules\Product\Entities\Product'))){
            return response()->json(['status'=>'permission denied'], 401);
        }
        $product = Product::where('id', $id)->first();
        return view('product::view_product', compact('product'));
    }

    public function edit_product(Request $request,$id)
    {
        if(!$this->user->can('edit_product',app('Modules\Product\Entities\Product'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        if($request->isMethod('post')){
            try{  
                $data=$request->all();
                $data['company_id']=$request->user()->company_id;
                if (array_key_exists('image', $data)) {
                    $img = img_process($data['image'],'app/public/uploads');
                    $data['image'] = $img;
                }
                Product::updateProduct($data,$id);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            $product = Product::whereId($id)->first();
            $categories = Category::get();
            $suppliers = Supplier::get();
            $units = Unit::get();
            return view('product::edit_product',compact('product','categories','units','suppliers'));exit;
        }
    }
    public function get_price($id)
    {
        $product = Product::select('price','head_code','product_name')->where('id',$id)->first();
        $price = $product->price;
        $data = ['price'=>$price,'name' => $product->product_name, 'head_code' => $product->head_code];
        return $data;
    }

    public function del_product($id)
    {
        
        $price = $product->price;
        $data = ['price'=>$price,'name' => $product->product_name];
        return $data;
        try{
                $product = Product::select('price','product_name')->where('id',$id)->first();  
                $data=$request->all();
                $data['company_id']=$request->user()->company_id;
                if (array_key_exists('image', $data)) {
                    $img = img_process($data['image'],'app/public/uploads');
                    $data['image'] = $img;
                }
                Product::updateProduct($data,$id);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            } 
    }

    // Product Reports
    public function productReports(){
        $product = Product::all();
        $companies = Company::where('parent_id', 1)->get();
        $products = Product::with('company')->get();
        return view('product::product_reports', compact('products', 'product', 'companies'));
    }

    // Print Product Reports
    public function printProductReport(){
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
        $title = 'All Products List';
        $products = Product::with('company')->get();
        $company_info = Company::where('id',$this->user->company_id)->first();
        $pdf = PDF::loadView('product::print_product_report',compact('company_info','products','pdf_style','title'));
        $pdf->setPaper('A4', 'potrait');
        $name = "AllProductReport.pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }

    // Product Searching
    public function productSearching(Request $request){
        $product_id = $request->product_id;
        $company_id = $request->company_id;
        $startPrice = $request->startPrice;
        $endPrice   = $request->endPrice;

        if($company_id){
            $products = Product::where('company_id', $company_id)
                                ->get(); 
        }elseif($product_id && $company_id && $startPrice && $endPrice){
            $products = Product::where('product_id', $product_id)
                                ->where('company_id', $company_id)
                                ->whereBetween('price', [$startPrice, $endPrice])
                                ->get();
        }elseif($product_id && $company_id){
            $products = Product::where('product_id', $product_id)
                                ->where('company_id', $company_id)
                                ->get();
        }elseif($product_id){
            $products = Product::where('product_id', $product_id)
                                ->get();
        }elseif($company_id && $startPrice && $endPrice){
            $products = Product::where('company_id', $company_id)
                                ->whereBetween('price', [$startPrice, $endPrice])
                                ->get();
        }elseif($startPrice && $endPrice){
            $products = Product::whereBetween('price', [$startPrice, $endPrice])
                                ->get();
        }else{
            return "error";
        }

        return view('product::productReportView', compact('products'));
        
    }
}
