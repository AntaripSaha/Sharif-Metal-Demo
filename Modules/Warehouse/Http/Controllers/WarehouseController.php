<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\WarehouseProducts;
use Modules\Warehouse\Entities\WarehouseInserts;
use Modules\Seller\Entities\SellRequest;
use Modules\Company\Entities\Company;
use Modules\Product\Entities\Product;
use Modules\Seller\Entities\RequestProduct;
use Modules\Customer\Entities\Customer;
use Modules\Accounts\Entities\Transactions;
use Modules\Accounts\Entities\Accounts;
use Modules\Supplier\Entities\Supplier;
use PDF;
use App\User;
use Carbon\Carbon;
use DB;
use DataTables;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class WarehouseController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (!$this->user->can('browse', app('Modules\Warehouse\Entities\Warehouse'))) {
            return redirect()->route('users.index')->with('flash', array('status' => 'error', 'message' => 'permission denied'));
        }
        $data = Warehouse::get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btnDel = "";
                    $btn_view = '';
                    $btn_edit = '';
                    if ($this->user->isOfficeAdmin()) {
                        $btn_view = '<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-' . $row->id . '"> View</a>';
                        $btn_edit = '<a class="mr-2 btn btn-info btn-sm cp edit-tr" id="edit-tr-' . $row->id . '">Edit</a>';
                    }
                    if ($this->user->can('view', app('Modules\Warehouse\Entities\Warehouse'))) {
                        $btn_view = '<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-' . $row->id . '"> View</a>';
                    }
                    if ($this->user->can('edit', app('Modules\Warehouse\Entities\Warehouse'))) {
                        $btn_edit = '<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-' . $row->id . '"> Edit</a>';
                    }
                    return $btn_view . $btn_edit;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $status = 'Active';
                    } else {
                        $status = 'Inactive';
                    }
                    return $status;
                })
                ->addColumn('input', function ($row) {
                    $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="' . $row->id . '" >';
                    return $input;
                })
                ->rawColumns(['action', 'input'])
                ->make(true);
            exit;
        }
        return view('warehouse::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function add_warehouse(Request $request)
    {
        if (!$this->user->can('add', app('Modules\Warehouse\Entities\Warehouse'))) {
            return redirect()->route('users.index')->with('flash', array('status' => 'error', 'message' => 'permission denied'));
        }
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                Warehouse::createWarehouse($data);
                return response()->json(['status' => 'success'], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => $e->getMessage()], 500);
            }
        } else {
            return view('warehouse::add_warehouse');
            exit;
        }
    }

    public function view_warehouse($id)
    {
        if (!$this->user->can('view', app('Modules\Warehouse\Entities\Warehouse'))) {
            return response()->json(['status' => 'permission denied'], 401);
        }
        $warehouse = Warehouse::where('id', $id)->first();
        return view('warehouse::view_warehouse', compact('warehouse'));
    }

    /**
     * Show the form for editing the Warehouse.
     */
    public function edit_warehouse(Request $request, $id)
    {
        if (!$this->user->can('edit', app('Modules\Warehouse\Entities\Warehouse'))) {
            return redirect()->route('users.index')->with('flash', array('status' => 'error', 'message' => 'permission denied'));
        }
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                Warehouse::updateWarehouse($data, $id);
                return response()->json(['status' => 'success'], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => $e->getMessage()], 500);
            }
        } else {
            $warehouse = Warehouse::whereId($id)->first();
            return view('warehouse::edit_warehouse', compact('warehouse'));
            exit;
        }
    }

    public function wareproducts($ware_id = null)
    {
        $edate = date('Y-m-d');
        $warehouses = Warehouse::get();
        $company_info = Company::where('id', $this->user->company_id)->first();
        if ($ware_id == null) {
            $products = WarehouseProducts::with('warehouse', 'products')->paginate(50);
        } else {
            $products = WarehouseProducts::with('warehouse', 'products')->where('warehouse_id', $ware_id)->paginate(50);
        }
        return view('warehouse::warehouse_products', compact('products', 'edate', 'company_info', 'warehouses'));
    }

    public function ware_product($id = null)
    {
        $product_id = Product::select('id')->where('product_id', $id)->first();
        if ($product_id !== null) {
            $p_id = $product_id->id;
            $product = WarehouseProducts::with('products', 'warehouse')->where('product_id', $p_id)->first();
            return view('warehouse::ware_product', compact('product'));
        } else {
            $product = null;
            return view('warehouse::ware_product', compact('product'));
        }
    }


    public function add_wareproduct(Request $request)
    {
        if (!$this->user->can('add_wareproducts', app('Modules\Warehouse\Entities\Warehouse'))) {
            return redirect()->route('users.index')->with('flash', array('status' => 'error', 'message' => 'permission denied'));
        }
        if ($request->isMethod('post')) {
            // try{
            $data = $request->all();
            $data['created_by'] = $this->user->id;
            $n_data['v_date'] = $request->v_date;
            $n_data['chalan_no'] = $request->chalan_no;

            foreach ($data['product_id'] as $key => $value) {
                $n_data['product_id'] = $data['product_id'][$key];
                $n_data['warehouse_id'] = $data['warehouse_id'][$key];
                $n_data['stck_q'] = $data['stck_q'][$key];
                $n_data['created_by'] = $data['created_by'];

                $product = WarehouseProducts::where('product_id', $data['product_id'][$key])->where('warehouse_id', $data['warehouse_id'][$key])->first();
                if ($product == null) {
                    WarehouseProducts::insert_product($n_data);
                } else {
                    $prev_q = $product['stck_q'];
                    $new_q = $prev_q + $data['stck_q'][$key];
                    WarehouseProducts::where('id', $product->id)->update(['stck_q' => $new_q, 'head_code' => $data['head_code'][$key]]);
                }
                $n_data['in_qnt'] = $data['stck_q'][$key];
                WarehouseInserts::insertware_product($n_data);
                $prod_info = Product::with('supplier')->where('id', $data['product_id'][$key])->first();
                $bprice = $data['stck_q'][$key] * $prod_info->production_price;
                $supplier_id = $prod_info->supplier_id;
                if ($supplier_id !== NULL) {
                    $sup_coa = Accounts::select('HeadCode')->where('supplier_id', $supplier_id)->first();
                    // Inventory Debit //
                    $invdebit = array(
                        'VNo'            =>  $request->chalan_no,
                        'Vtype'          =>  'Purchase',
                        'VDate'          =>  $request->v_date,
                        'COAID'          =>  '10301',
                        'Narration'      =>  'Inventory debit For Purchase No -  ' . $request->chalan_no,
                        'Debit'          =>  $bprice,
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'created_by'     =>  $this->user->id,
                        'updated_by'     =>  $this->user->id,
                        'IsAppove'       =>  1
                    );
                    Transactions::createTransaction($invdebit);
                    // Supplier Credit //
                    $supcr = array(
                        'VNo'            =>  $request->chalan_no,
                        'Vtype'          =>  'Purchase',
                        'VDate'          =>  $request->v_date,
                        'COAID'          =>  $sup_coa->HeadCode,
                        'Narration'      =>  $prod_info->supplier->supplier_name . ' Credit For Invoice No' . $request->chalan_no,
                        'Debit'          =>  0,
                        'Credit'         =>  $bprice, //Buy Amount
                        'IsPosted'       =>  1,
                        'created_by'     =>  $this->user->id,
                        'updated_by'     =>  $this->user->id,
                        'IsAppove'       =>  1
                    );
                    Transactions::createTransaction($supcr);
                    // COMPANMY EXPENSE Debit //
                    $comcr = array(
                        'VNo'            =>  $request->chalan_no,
                        'Vtype'          =>  'Purchase',
                        'VDate'          =>  $request->v_date,
                        'COAID'          =>  '40501',
                        'Narration'      =>  'Finish Good Expense Credit For ' . $request->chalan_no,
                        'Debit'          =>  $bprice,
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'created_by'     =>  $this->user->id,
                        'updated_by'     =>  $this->user->id,
                        'IsAppove'       =>  1
                    );
                    Transactions::createTransaction($comcr);
                } else {
                    // Inventory Debit //
                    $invdebit = array(
                        'VNo'            =>  $request->chalan_no,
                        'Vtype'          =>  'Purchase',
                        'VDate'          =>  $request->v_date,
                        'COAID'          =>  '10301',
                        'Narration'      =>  'Inventory debit For Purchase No -  ' . $request->chalan_no,
                        'Debit'          =>  $bprice,
                        'Credit'         =>  0,
                        'IsPosted'       =>  1,
                        'created_by'     =>  $this->user->id,
                        'updated_by'     =>  $this->user->id,
                        'IsAppove'       =>  1
                    );
                    Transactions::createTransaction($invdebit);
                    // COMPANMY EXPENSE CREDit //
                    $comcr = array(
                        'VNo'            =>  $request->chalan_no,
                        'Vtype'          =>  'Purchase',
                        'VDate'          =>  $request->v_date,
                        'COAID'          =>  '40501',
                        'Narration'      =>  'Finish Good Expense Credit For ' . $request->chalan_no,
                        'Debit'          =>  0,
                        'Credit'         =>  $bprice,
                        'IsPosted'       =>  1,
                        'created_by'     =>  $this->user->id,
                        'updated_by'     =>  $this->user->id,
                        'IsAppove'       =>  1
                    );
                    Transactions::createTransaction($comcr);
                }
            }
            return response()->json(['status' => 'success'], 200);
            // }catch(\Exception $e){
            //     return response()->json(['status'=>$e->getMessage()], 500);
            // }
        } else {
            $warehouses = Warehouse::with('products')->get();
            $products = Product::get();
            return view('warehouse::add_product', compact('warehouses', 'products'));
            exit;
        }
    }

    public function prod_requests(Request $request)
    {

        if (!$this->user->can('view_prodreq', app('Modules\Warehouse\Entities\Warehouse'))) {
            return redirect()->route('users.index')->with('flash', array('status' => 'error', 'message' => 'permission denied'));
        }
        $edate = date('Y-m-d');
        $data = SellRequest::with('customer', 'seller')->where('is_approved', 1)->where('is_delivered', 0)->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btn_view = '';
                    $btn_view = '<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-' . $row->id . '"> View</a>';
                    return $btn_view;
                })
                ->addColumn('input', function ($row) {
                    $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="' . $row->id . '" >';
                    return $input;
                })
                ->rawColumns(['action', 'input'])
                ->make(true);
            exit;
        }
        return view('warehouse::prod_request');
    }

    public function sell_req_details($id)
    {
        $req = SellRequest::with('customer')->where('id', $id)->first();
        $req_products = RequestProduct::with('products')->where('req_id', $id)->get();
        $is_delivered = $req->is_delivered;
        $v_date = $req->v_date;
        $cus_name = $req->customer->customer_name;
        $req_id = $id;
        $warehouses = Warehouse::get();
        return view('warehouse::prod_req_details', compact('req', 'req_id', 'req_products', 'cus_name', 'v_date', 'is_delivered', 'warehouses'));
    }
    public function prod_q($prod_id, $req_id, $head_code)
    {
        // return $head_code;exit();
        if ($head_code == 'null') {
            $prod_id = Product::select('id', 'product_name', 'product_id')->where('id', $prod_id)->orwhere('product_name', $prod_id)->first();
        } else {
            $prod_id = Product::select('id', 'product_name', 'product_id')->where('id', $prod_id)->where('head_code', $head_code)->orwhere('product_name', $prod_id)->first();
        }

        $warehouses = WarehouseProducts::select("warehouse_id")->where('product_id', $prod_id->id)->groupBy("warehouse_id")->get();

        $warehouse_data = [];
        foreach ($warehouses as $warehouse) {
            array_push($warehouse_data, Warehouse::where("id", $warehouse->warehouse_id)->select("id", "name")->first());
        }

        $data = ['warehouses' => $warehouse_data];
        return $data;

        $asked_q = RequestProduct::select('qnty')->where('product_id', $prod_id->id)->where('req_id', $req_id)->first();
        $req_qnt = $asked_q->qnty;
        if ($prod_id != null) {
            $qnt = WarehouseProducts::select('stck_q', 'sell_q')->where('product_id', $prod_id->id)->where('warehouse_id', $ware_id)->first();
            if ($qnt != null) {
                $qnty = $qnt->stck_q - $qnt->sell_q;
                $pro_id = $prod_id->id;
                $prod_name = $prod_id->product_name;
                $bar_code = $prod_id->product_id;
            } else {
                $qnty = 0;
                $pro_id = $prod_id->id;
                $prod_name = $prod_id->product_name;
                $bar_code = $prod_id->product_id;
            }
        } else {
            $qnty = 0;
            $pro_id = 0;
            $prod_name = '';
            $bar_code = '';
        }

        $data = ['prod_id' => $pro_id, 'stck_q' => $qnty, 'prod_name' => $prod_name, 'bar_code' => $bar_code, 'req_qnt' => $req_qnt];
        return $data;
    }


    public function prod_quantity($prod_id, $ware_id, $req_id, $head_code)
    {


        $warehouse_product = WarehouseProducts::select('stck_q', 'sell_q')->where('product_id', $prod_id)->where('warehouse_id', $ware_id)->first();

        if ($warehouse_product) {
            $qnty = $warehouse_product->stck_q - $warehouse_product->sell_q;
        } else {
            $qnty = 0;
        }

        $product = Product::select('id', 'product_name', 'product_id')->where('id', $prod_id)->first();

        $request_product = RequestProduct::select('qnty')->where('product_id', $prod_id)->where('req_id', $req_id)->first();

        if ($request_product) {
            $req_qnt = $request_product->qnty;
        } else {
            $req_qnt = 0;
        }

        $prod_name = $product->product_name;
        $bar_code = $product->product_id;

        $data = ['prod_id' => $prod_id, 'stck_q' => $qnty, 'prod_name' => $prod_name, 'bar_code' => $bar_code, 'req_qnt' => $req_qnt];
        return $data;


        if ($head_code == 'null') {
            $prod_id = Product::select('id', 'product_name', 'product_id')->where('id', $prod_id)->first();
        } else {
            $prod_id = Product::select('id', 'product_name', 'product_id')->where('id', $prod_id)->where('head_code', $head_code)->first();
        }

        if ($prod_id) {
            $asked_q = RequestProduct::select('qnty')->where('product_id', $prod_id->id)->where('req_id', $req_id)->first();

            $req_qnt = 0;
            if ($asked_q) {
                $req_qnt = $asked_q->qnty;
                if ($prod_id != null) {
                    return $qnt = WarehouseProducts::select('stck_q', 'sell_q')->where('product_id', $prod_id->id)->where('warehouse_id', $ware_id)->first();
                    if ($qnt != null) {
                        $qnty = $qnt->stck_q - $qnt->sell_q;
                        $pro_id = $prod_id->id;
                        $prod_name = $prod_id->product_name;
                        $bar_code = $prod_id->product_id;
                    } else {
                        $qnty = 0;
                        $pro_id = $prod_id->id;
                        $prod_name = $prod_id->product_name;
                        $bar_code = $prod_id->product_id;
                    }
                } else {
                    $qnty = 0;
                    $pro_id = 0;
                    $prod_name = '';
                    $bar_code = '';
                }
            } else {
                $qnty = 0;
                $pro_id = 0;
                $prod_name = '';
                $bar_code = '';
            }
        } else {
            $qnty = 0;
            $pro_id = 0;
            $prod_name = '';
            $bar_code = '';
        }




        $data = ['prod_id' => $pro_id, 'stck_q' => $qnty, 'prod_name' => $prod_name, 'bar_code' => $bar_code, 'req_qnt' => $req_qnt];
        return $data;
    }


    // Invoice No
    private function voucher_no()
    {
        $prefix = 'AB';
        $serialNo = 1;
        $current_voucher_no = SellRequest::max('voucher_no');
        if ($current_voucher_no == null) {
            $voucher_no = $prefix . '-000' . $serialNo;
        } else {
            $voucherIncrementNumber = explode("-", $current_voucher_no);
            $currentPrefix = $voucherIncrementNumber[0];
            $currentSerialNo = $voucherIncrementNumber[1];
            $currentSerialNo++;
            if ($currentSerialNo >= 9999) {
                $currentPrefix++;
                $currentSerialNo = 1;
            }

            if ($currentSerialNo <= 9) {
                $nextSerialNo = '000' . $currentSerialNo;
            } elseif ($currentSerialNo <= 99) {
                $nextSerialNo = '00' . $currentSerialNo;
            } elseif ($currentSerialNo <= 999) {
                $nextSerialNo = '0' . $currentSerialNo;
            } else {
                $nextSerialNo = $currentSerialNo;
            }
            $voucher_no = $currentPrefix . "-" . $nextSerialNo;
        }
        return $voucher_no;
    }

    //challan no
    private function challan_no()
    {

        $prefix = 'AB';
        $serialNo = 1;
        $current_challan_no = SellRequest::max('req_id');
        if ($current_challan_no == null) {
            $challan_no = $prefix . '-000' . $serialNo;
        } else {
            $voucherIncrementNumber = explode("-", $current_challan_no);
            $currentPrefix = $voucherIncrementNumber[0];
            $currentSerialNo = $voucherIncrementNumber[1];
            $currentSerialNo++;
            if ($currentSerialNo >= 9999) {
                $currentPrefix++;
                $currentSerialNo = 1;
            }

            if ($currentSerialNo <= 9) {
                $nextSerialNo = '000' . $currentSerialNo;
            } elseif ($currentSerialNo <= 99) {
                $nextSerialNo = '00' . $currentSerialNo;
            } elseif ($currentSerialNo <= 999) {
                $nextSerialNo = '0' . $currentSerialNo;
            } else {
                $nextSerialNo = $currentSerialNo;
            }
            $challan_no = $currentPrefix . "-" . $nextSerialNo;
        }
        return $challan_no;
    }

    public function deliver(Request $request)
    {


        try {
            $deliver_products = [];
            $request_id = $request->request_id;
            $edate = date('Y-m-d');
            $data = $request->all();
            $gift = $request->gift;

            if ($request->com_id && $request->product_id) {

                //Combo product Insert OUT//
                foreach ($data['com_id'] as $key => $value) {

                    //Product To Deliver and it's Quantity
                    $prod_info = ['product_id' => $data['com_outproduct_id'][$key], 'qnt' => $data['com_out_qnt'][$key]];
                    array_push($deliver_products, $prod_info);
                    //Warehouse Insert Combo Product//
                    $comout = $data['com_out_qnt'][$key];
                    $n_data['product_id'] = $data['com_id'][$key];
                    $n_data['out_qnt'] = $data['com_out_qnt'][$key];
                    $n_data['warehouse_id'] = $data['com_warehouse_id'][$key];
                    $n_data['company_id'] = $data['company_id'];
                    $n_data['v_date'] = $data['v_date'];

                    $n_data['chalan_no'] = $data['chalan_no'];
                    $n_data['created_by'] = $this->user->id;
                    $n_data['del_date'] = $edate;
                    WarehouseInserts::insertware_product($n_data);

                    //Update the stock//
                    $product_qnt = WarehouseProducts::select('sell_q', 'id')->where('warehouse_id', $data['com_warehouse_id'][$key])->where('product_id', $data['com_id'][$key])->first();
                    $id = $product_qnt->id;
                    $out = $data['com_out_qnt'][$key];
                    $new_q = $product_qnt->sell_q + $out;
                    WarehouseProducts::where('id', $id)->update(['sell_q' => $new_q]);

                    $combo_products = Product::select('combo_ids')->where('id', $data['com_id'][$key])->first();
                    $c_p = $combo_products->combo_ids;
                    $ccp = explode('_', $c_p);
                    $barcode = Product::select('product_id')->where('id', $data['com_outproduct_id'][$key])->first();;
                    $index = array_search($barcode->product_id, $ccp);
                    if ($index !== FALSE) {
                        unset($ccp[$index]);
                    }
                    foreach ($ccp as $key => $value) {
                        $prodc_id = Product::select('id')->where('product_id', $value)->first();
                        $product_qnt = WarehouseProducts::select('stck_q', 'id')->where('product_id', $prodc_id->id)->first();
                        $id = $product_qnt->id;
                        $new_q = $product_qnt->stck_q + $comout;
                        WarehouseProducts::where('id', $id)->update(['stck_q' => $new_q]);
                    }
                }


                //Product Insert and Update from Stock//
                foreach ($data['product_id'] as $key => $value) {

                    $prod_info = ['product_id' => $data['product_id'][$key], 'qnt' => $data['out_qnt'][$key]];
                    array_push($deliver_products, $prod_info);

                    $n_data['product_id'] = $data['product_id'][$key];
                    $n_data['out_qnt'] = $data['out_qnt'][$key];
                    $n_data['warehouse_id'] = $data['warehouse_id'][$key];
                    $n_data['company_id'] = $request->user()->company_id;
                    $n_data['v_date'] = $data['v_date'];
                    $n_data['chalan_no'] = $data['chalan_no'];
                    $n_data['created_by'] = $this->user->id;
                    $n_data['del_date'] = $edate;
                    WarehouseInserts::insertware_product($n_data);
                }



                foreach ($data['product_id'] as $key => $value) {
                    $product_qnt = WarehouseProducts::select('sell_q', 'id')->where('warehouse_id', $data['warehouse_id'][$key])->where('product_id', $data['product_id'][$key])->first();
                    $id = $product_qnt->id;
                    $out = $data['out_qnt'][$key];
                    $new_q = $product_qnt->sell_q + $out;
                    WarehouseProducts::where('id', $id)->update(['sell_q' => $new_q]);
                }
                //Update the delivery amount//
                foreach ($deliver_products as $key => $del_product) {
                    $pid = $del_product['product_id'];
                    $q = $del_product['qnt'];
                    $prod_delq = RequestProduct::select('del_qnt', 'id')->where('req_id', $request_id)->where('product_id', $pid)->first();
                    $up_q = $prod_delq->del_qnt + $q;
                    RequestProduct::where('id', $prod_delq->id)->update(['del_qnt' => $up_q]);
                }
                //Deliver amount Update in Sell Request//
                $pr_price = RequestProduct::with('products')->where('req_id', $request_id)->get();
                $sell_req = SellRequest::where('id', $request_id)->first();
                $del_amount = 0;
                $del_discount = 0;
                foreach ($pr_price as $product) {
                    $price = $product->products->price;
                    if ($product->prod_disc != null) {
                        $prod_dprice = ($product->del_qnt * $price) * $product->prod_disc / 100;
                        $del_discount = $del_discount + $prod_dprice;
                        $prod_qprice = ($product->del_qnt * $price) - $prod_dprice;
                        $del_amount = $del_amount + $prod_qprice;
                    } else {
                        $prod_qprice = $product->del_qnt * $price;
                        $del_amount = $del_amount + $prod_qprice;
                        $del_discount = null;
                    }
                }
                if ($sell_req->amount == $del_amount) {
                    $fully_delivered = 1;
                } else {
                    $fully_delivered = 0;
                }
                SellRequest::where('voucher_no', $data['chalan_no'])->update(
                    [
                        'is_delivered' => 1, 'del_date' => $edate, 'del_amount' => $del_amount, 'del_discount' => $del_discount,
                        'transp_name' => $data['transp_name'],
                        'deliv_pname' => $data['deliv_pname'],
                        'gift' => $data['gift'],
                        'fully_delivered' => $fully_delivered,
                    ]
                );
                //Inventory Credit And Customer Debit//
                $coaid = Accounts::select('HeadCode')->where('customer_id', $sell_req->customer_id)->first();
                $cusinfo = Customer::where('id', $sell_req->customer_id)->first();
                $cus_coa = $coaid->HeadCode;
                $IsPosted = 1;
                $IsAppove = 1;
                $created_by = $this->user->id;
                $updated_by = $this->user->id;
                //Customer debit for Product Value
                $customerdebit = array(
                    'VNo'            =>  $data['chalan_no'],
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $data['v_date'],
                    'COAID'          =>  $cus_coa,
                    'Narration'      =>  'Customer debit For Invoice No -  ' . $request_id . ' Customer ' . $cusinfo->customer_name,
                    'Debit'          =>  $del_amount,
                    'Credit'         =>  0,
                    'IsPosted'       =>  $IsPosted,
                    'created_by'     =>  $created_by,
                    'updated_by'     =>  $updated_by,
                    'company_id'     =>  $sell_req['company_id'],
                    'IsAppove'       =>  $IsAppove
                );
                Transactions::createTransaction($customerdebit);
                ///Inventory credit
                $coscr = array(
                    'VNo'            =>  $data['chalan_no'],
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $data['v_date'],
                    'COAID'          =>  10301,
                    'Narration'      =>  'Inventory credit For Invoice No' . $request_id,
                    'Debit'          =>  0,
                    'Credit'         =>  $del_amount, //Deliver Amount
                    'IsPosted'       =>  $IsPosted,
                    'created_by'     =>  $created_by,
                    'updated_by'     =>  $updated_by,
                    'company_id'     =>  $sell_req['company_id'],
                    'IsAppove'       =>  $IsAppove
                );
                Transactions::createTransaction($coscr);
                return response()->json(['status' => 'success', 'data' => $request_id], 200);
                exit();
            } else {

                $voucher_no = $this->voucher_no();
                $challan_no = $this->challan_no();
                $sell_req = SellRequest::where('id', $request_id)->first();

                foreach ($data['product_id'] as $key => $value) {

                    //Product To Deliver and it's Quantity
                    $prod_info = ['product_id' => $data['product_id'][$key], 'qnt' => $data['out_qnt'][$key]];
                    array_push($deliver_products, $prod_info);

                    $n_data['product_id'] = $data['product_id'][$key];
                    $n_data['out_qnt'] = $data['out_qnt'][$key];
                    $n_data['warehouse_id'] = $data['warehouse_id'][$key];
                    $n_data['company_id'] = $sell_req['company_id'];
                    $n_data['v_date'] = $data['v_date'];
                    $n_data['chalan_no'] = $voucher_no;
                    $n_data['created_by'] = $this->user->id;
                    $n_data['del_date'] = $edate;
                    WarehouseInserts::insertware_product($n_data);
                }
                foreach ($data['product_id'] as $key => $value) {
                    $product_qnt = WarehouseProducts::select('sell_q', 'id')->where('warehouse_id', $data['warehouse_id'][$key])->where('product_id', $data['product_id'][$key])->first();
                    $id = $product_qnt->id;
                    $out = $data['out_qnt'][$key];
                    $new_q = $product_qnt->sell_q + $out;
                    WarehouseProducts::where('id', $id)->update(['sell_q' => $new_q]);
                }

                //Update the delivery amount//
                foreach ($deliver_products as $key => $del_product) {
                    $pid = $del_product['product_id'];
                    $q = $del_product['qnt'];
                    $prod_delq = RequestProduct::select('del_qnt', 'id')->where('req_id', $request_id)->where('product_id', $pid)->first();
                    $up_q = $prod_delq->del_qnt + $q;
                    RequestProduct::where('id', $prod_delq->id)->update(['del_qnt' => $up_q]);
                }

                //Deliver amount Update//
                $pr_price = RequestProduct::with('products')->where('req_id', $request_id)->get();
                $del_amount = 0;
                $del_discount = 0;
                foreach ($pr_price as $product) {
                    $price = $product->products->price;

                    if ($product->prod_disc != null) {
                        $prod_dprice = ($product->del_qnt * $price) * $product->prod_disc / 100;
                        $del_discount = $del_discount + $prod_dprice;
                        $prod_qprice = ($product->del_qnt * $price) - $prod_dprice;
                        $del_amount = $del_amount + $prod_qprice;
                    } else {
                        $prod_qprice = $product->del_qnt * $price;
                        $del_amount = $del_amount + $prod_qprice;
                        $del_discount = null;
                    }
                }
                if ($sell_req->sale_disc !== null) {
                    $sale_disc = $del_amount * ($sell_req->sale_disc / 100);
                    $del_discount = $del_discount + $sale_disc;
                    $del_amount = $del_amount - $sale_disc;
                }
                if ($sell_req->amount == $del_amount) {
                    $fully_delivered = 1;
                } else {
                    $fully_delivered = 0;
                }


                SellRequest::where('id', $request_id)->update([
                    'is_delivered' => 1,
                    'del_date' => $edate,
                    'del_amount' => $del_amount,
                    'del_discount' => $del_discount,
                    'transp_name' => $data['transp_name'],
                    'deliv_pname' => $data['deliv_pname'],
                    'fully_delivered' => $fully_delivered,
                    'gift' => $data['gift'],
                    'req_id' => $challan_no,
                    'challan_no' => $challan_no,
                ]);

                //Inventory Credit And Customer Debit//
                $coaid = Accounts::select('HeadCode')->where('customer_id', $sell_req->customer_id)->first();
                $cusinfo = Customer::where('id', $sell_req->customer_id)->first();
                $cus_coa = $coaid->HeadCode;
                $IsPosted = 1;
                $IsAppove = 1;
                $created_by = $this->user->id;
                $updated_by = $this->user->id;
                //Customer debit for Product Value
                $customerdebit = array(
                    'VNo'            =>  $data['chalan_no'],
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $data['v_date'],
                    'COAID'          =>  $cus_coa,
                    'Narration'      =>  'Customer debit For Invoice No -  ' . $request_id . ' Customer ' . $cusinfo->customer_name,
                    'Debit'          =>  $del_amount,
                    'Credit'         =>  0,
                    'IsPosted'       =>  $IsPosted,
                    'created_by'     =>  $created_by,
                    'updated_by'     =>  $updated_by,
                    'company_id'     =>  $sell_req['company_id'],
                    'IsAppove'       =>  $IsAppove
                );
                Transactions::createTransaction($customerdebit);
                ///Inventory credit
                $coscr = array(
                    'VNo'            =>  $data['chalan_no'],
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $data['v_date'],
                    'COAID'          =>  10301,
                    'Narration'      =>  'Inventory credit For Invoice No' . $request_id,
                    'Debit'          =>  0,
                    'Credit'         =>  $del_amount, //Deliver Amount
                    'IsPosted'       =>  $IsPosted,
                    'created_by'     =>  $created_by,
                    'updated_by'     =>  $updated_by,
                    'company_id'     =>  $sell_req['company_id'],
                    'IsAppove'       =>  $IsAppove
                );
                Transactions::createTransaction($coscr);

                if (RequestProduct::with('products')->where('req_id', $data['request_id'])->where('qnty', '!=', 'del_qnt')->select('id')->count() < 1) {
                    $sell_request = SellRequest::where('voucher_no', $data['chalan_no'])->first();
                    $sell_request->fully_delivered = true;
                    $sell_request->save();
                }

                return response()->json(['status' => 'success', 'data' => $data['chalan_no']], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => $e->getMessage()], 500);
        }
    }




    public function combo_prod($id, $ware_id)
    {
        $products = Product::select('id')->where('combo_ids', 'like', '%' . $id . '%')->get();
        $com_p = [];
        foreach ($products as $key => $value) {
            $w_p = WarehouseProducts::with('warehouse', 'products')->where('warehouse_id', $ware_id)->where('product_id', $value->id)->first();
            $com_p[$key] = $w_p;
        }
        return $com_p;
    }


    public function print_chalan($chalan_no)
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
        $title = 'Challan';
        $chalan = SellRequest::with('customer')->where('voucher_no', $chalan_no)->first();
        $products = RequestProduct::with('products')->where('req_id', $chalan->id)->where("del_qnt", "!=", 0)->get();
        $company_info = Company::where('id', $chalan->company_id)->first();
        $sales_person_name = User::where("id", $chalan->seller_id)->select("name", "user_id")->first();
        $pdf = PDF::loadView('warehouse::chalan', compact('chalan', 'company_info', 'products', 'pdf_style', 'title', 'sales_person_name'));
        $pdf->setPaper('A4', 'potrait');
        $name = "Challan" . $chalan->voucher_no . ".pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }

    public function stock($ware_id = null)
    {
        $edate = date('Y-m-d');
        $warehouses = Warehouse::get();
        $company_info = Company::where('id', $this->user->company_id)->first();
        if ($ware_id == null) {
            $products = WarehouseProducts::with('warehouse', 'products')->paginate(50);
        } else {
            $products = WarehouseProducts::with('warehouse', 'products')->where('warehouse_id', $ware_id)->paginate(10);
        }
        return view('warehouse::stock', compact('products', 'edate', 'company_info', 'warehouses'));
    }


    //edit stock modal function start
    public function edit_stock_modal($id)
    {
        $warehouse_product = WarehouseProducts::where("id", $id)->select("id", "product_id", "warehouse_id", "stck_q", "sell_q")->first();
        return view('warehouse::stock.edit', compact('warehouse_product'));
    }
    //edit stock modal function end

    //edit stock start
    public function edit_stock(Request $request, $id)
    {
        $request->validate([
            "type" => "required",
            "quantity" => "required",
            "chalan_no" => "required",
            "v_date" => "required",
        ]);
        try {
            $warehouse_product = WarehouseProducts::where("id", $id)->first();

            if ($request->type == "In") {
                $warehouse_product->stck_q += $request->quantity;
                $warehouse_product->save();

                $warehouse_insert = new WarehouseInserts();
                $warehouse_insert->product_id = $warehouse_product->product_id;
                $warehouse_insert->warehouse_id = $warehouse_product->warehouse_id;
                $warehouse_insert->created_by = auth('web')->user()->id;
                $warehouse_insert->v_date = $request->v_date;
                $warehouse_insert->chalan_no = $request->chalan_no;
                $warehouse_insert->in_qnt = $request->quantity;
                $warehouse_insert->out_qnt = 0;
                $warehouse_insert->save();
            } else {
                $warehouse_product->sell_q += $request->quantity;
                $warehouse_product->save();

                $warehouse_insert = new WarehouseInserts();
                $warehouse_insert->product_id = $warehouse_product->product_id;
                $warehouse_insert->warehouse_id = $warehouse_product->warehouse_id;
                $warehouse_insert->created_by = auth('web')->user()->id;
                $warehouse_insert->v_date = $request->v_date;
                $warehouse_insert->chalan_no = $request->chalan_no;
                $warehouse_insert->in_qnt = 0;
                $warehouse_insert->out_qnt = $request->quantity;
                $warehouse_insert->save();
            }
            return back()->with('success', 'Quantity Updated');
        } catch (Exception $e) {
            return back();
        }
    }
    //edit stock end

    public function stockproduct($prod_id)
    {
        $product_id = Product::select('id')->where('product_id', $prod_id)->first();
        if ($product_id !== null) {
            $p_id = $product_id->id;
            $product = WarehouseProducts::with('products', 'warehouse')->where('product_id', $p_id)->first();
            return view('warehouse::stock_product', compact('product'));
        } else {
            $product = null;
            return view('warehouse::stock_product', compact('product'));
        }
    }

    // Stock Report Details
    public function stock_details()
    {
        $warehouses = Warehouse::get();
        $stock_report_details = WarehouseInserts::with('products', 'warehouse')->select("product_id")->groupBy('product_id')->paginate(20);

        // return $stock_report_details[0]->product->warehouse_insert;
        return view('warehouse::StockReportDetails.stockReportDetails', compact('warehouses', 'stock_report_details'));
    }

    // Stock Movement Report Function Start
    public function stockMovementReport()
    {
        $warehouses = Warehouse::get();
        $products = Product::select('id', 'product_id', 'product_name', 'head_code')->get();
        $stockMovementReport = [];

        foreach ($products as $product) {
            $inwards = WarehouseInserts::where('product_id', $product->id)->sum('in_qnt');
            $outwards = WarehouseInserts::where('product_id', $product->id)->sum('out_qnt');

            array_push($stockMovementReport, [
                'particulars'       => $product->product_id . " - " . $product->product_name,
                'head_code'         => $product->head_code,
                'inwards'           => $inwards,
                'outwards'          => $outwards,
                'closing_balance'   => $inwards - $outwards
            ]);
        }

        $data = $this->paginating($stockMovementReport);
        return view('warehouse::StockReportDetails.stockMovementReport', compact('data', 'warehouses'));
    }
    // Stock Movement Report Function End

    // Custome Paginate Function Start
    public function paginating($items, $perPage = 15, $page = null, $options = [], $nextPagePath = '/warehouse/stock_movement_report')
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options, $nextPagePath);
    }
    // Custome Paginate Function End

    // Stock Movement Report Print Start
    public function stockMovementReportPrint(Request $request)
    {
        $company_info = Company::where('id', $this->user->company_id)->first();
        $from = $request->from;
        $to = $request->to;
        $dateRange = null;
        if ($from && $to) {
            $products = Product::select('id', 'product_id', 'product_name', 'head_code')->get();
            $stockMovementReport = [];
            $dateRange = Carbon::parse($from)->toFormattedDateString() . " to " . Carbon::parse($to)->toFormattedDateString();
            foreach ($products as $product) {
                $inwards = WarehouseInserts::where('product_id', $product->id)
                    ->whereBetween('v_date', [$from, $to])
                    ->select("in_qnt")
                    ->sum('in_qnt');
                $outwards = WarehouseInserts::where('product_id', $product->id)
                    ->whereBetween('v_date', [$from, $to])
                    ->select("out_qnt")
                    ->sum('out_qnt');

                array_push($stockMovementReport, [
                    'particulars'       => $product->product_id . " - " . $product->product_name,
                    'head_code'         => $product->head_code,
                    'inwards'           => $inwards,
                    'outwards'          => $outwards,
                    'closing_balance'   => $inwards - $outwards
                ]);
            }
        } else {
            $products = Product::select('id', 'product_id', 'product_name', 'head_code')->get();
            $products_id = Product::select('id')->get();
            $stockMovementReport = [];
            $dateRange = "All";

            foreach ($products as $product) {
                $inwards = WarehouseInserts::where('product_id', $product->id)->select("in_qnt")->sum('in_qnt');
                $outwards = WarehouseInserts::where('product_id', $product->id)->select("out_qnt")->sum('out_qnt');

                array_push($stockMovementReport, [
                    'particulars' => $product->product_id . " - " . $product->product_name,
                    'head_code' => $product->head_code,
                    'inwards' => $inwards,
                    'outwards' => $outwards,
                    'closing_balance' => $inwards - $outwards
                ]);
            }
        }

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
        $title = 'Stock Movement Summary';
        $pdf = PDF::loadView('warehouse::StockReportDetails.printStockMovementReport', compact('company_info', 'stockMovementReport', 'pdf_style', 'title', 'dateRange'));
        $pdf->setPaper('A4', 'potrait');
        $name = "Stock Movement Report.pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }
    // Stock Movement Report Print End

    // Stock Movement Report Search Start
    public function stockMovementReportSearch(Request $request)
    {
        $warehouse_id = $request->warehouse_id;
        $product_id = $request->product_id;
        $from = $request->from;
        $to = $request->to;

        if ($from && $to) {
            $products = Product::select('id', 'product_id', 'product_name')->get();
            $stockMovementReport = [];


            foreach ($products as $product) {
                $inwards = WarehouseInserts::where('product_id', $product->id)
                    ->whereBetween('v_date', [$from, $to])
                    ->sum('in_qnt');
                $outwards = WarehouseInserts::where('product_id', $product->id)
                    ->whereBetween('v_date', [$from, $to])
                    ->sum('out_qnt');

                array_push($stockMovementReport, [
                    'particulars' => $product->product_id . " - " . $product->product_name,
                    'inwards' => $inwards,
                    'outwards' => $outwards,
                    'closing_balance' => $inwards - $outwards
                ]);
            }
            return view('warehouse::StockReportDetails.searchStockMovementReport', compact('stockMovementReport'));
        }
    }
    // Stock Movement Report Search End

    // Search Stock Report Details
    public function searchStockReportDetails(Request $request)
    {
        $warehouse_id = $request->warehouse_id;
        $product_id = $request->product_id;
        $from = $request->from;
        $to = $request->to;

        // get Product_code
        $product_code = Product::where('product_id', $product_id)->first();

        if ($product_code) {

            $stock_report_details = WarehouseInserts::with('products', 'warehouse')
                ->select("product_id")
                ->where('product_id', $product_code->id)
                ->whereBetween('v_date', [$from, $to])
                ->where('warehouse_id', $warehouse_id)
                ->groupBy('product_id')
                ->get();

            return view('warehouse::StockReportDetails.searchStockReportDetails', compact('stock_report_details', 'warehouse_id', 'from', 'to'));
        } else {
            $stock_report_details = WarehouseInserts::with('products', 'warehouse')
                ->select("product_id")
                ->whereBetween('v_date', [$from, $to])
                ->where('warehouse_id', $warehouse_id)
                ->groupBy('product_id')
                ->get();
            return view('warehouse::StockReportDetails.searchStockReportDetails', compact('stock_report_details', 'warehouse_id', 'from', 'to'));
        }
    }

    // Print Stock Details Report
    public function stockDetailsPrint(Request $request)
    {

        $from = $request->from;
        $to = $request->to;

        if ($request->warehouse_id == 0) {
            $warehouse_data = null;
        } else {
            $warehouse_data = Warehouse::where("id", $request->warehouse_id)->select("id", "name")->first();
        }

        if ($from == null && $to == null) {
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
            $title = 'Stock Report Details';

            if ($warehouse_data) {
                $stock_report_details = WarehouseInserts::with('products', 'warehouse')
                    ->where("warehouse_id", $warehouse_data->id)
                    ->select("product_id")->groupBy('product_id')->get();

                $data = [];
                foreach ($stock_report_details as $product) {
                    foreach ($product->products[0]->warehouse_insert->where('warehouse_id', $warehouse_data->id) as $key => $warehouse) {
                        array_push(
                            $data,
                            [
                                'name' => $warehouse->warehouse[0]->name,
                                'v_date' => $warehouse->v_date,
                                'chalan_no' => $warehouse->chalan_no,
                                'in_qnt' => $warehouse->in_qnt,
                                'out_qnt' => $warehouse->out_qnt,
                                'product_id' => $product->product_id,
                            ]
                        );
                    }
                }
            } else {
                $stock_report_details = WarehouseInserts::with('products', 'warehouse')->select("product_id")->groupBy('product_id')->get();

                $data = [];
                foreach ($stock_report_details as $product) {
                    foreach ($product->products[0]->warehouse_insert as $key => $warehouse) {
                        array_push(
                            $data,
                            [
                                'name' => $warehouse->warehouse[0]->name,
                                'v_date' => $warehouse->v_date,
                                'chalan_no' => $warehouse->chalan_no,
                                'in_qnt' => $warehouse->in_qnt,
                                'out_qnt' => $warehouse->out_qnt,
                                'product_id' => $product->product_id,
                            ]
                        );
                    }
                }
            }

            $new_data = collect($data)->groupBy('product_id');


            $company_info = Company::where('id', $this->user->company_id)->first();
            $pdf = PDF::loadView('warehouse::StockReportDetails.printStockReportDetails', compact('company_info', 'stock_report_details', 'pdf_style', 'title', 'from', 'to', 'warehouse_data', 'new_data'));
            $pdf->setPaper('A4', 'potrait');
            $name = "Stock Report Details.pdf";
            return $pdf->stream($name, array("Attachment" => false));
        } elseif ($from && $to) {
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
            $title = 'Stock Report Details';

            if ($warehouse_data) {
                $stock_report_details = WarehouseInserts::with('products', 'warehouse')
                    ->whereBetween('v_date', [$from, $to])
                    ->where("warehouse_id", $warehouse_data->id)
                    ->select("product_id")
                    ->groupBy('product_id')
                    ->get();

                $data = [];
                foreach ($stock_report_details as $product) {
                    foreach ($product->products[0]->warehouse_insert->whereBetween('v_date', [$from, $to])->where("warehouse_id", $warehouse_data->id) as $key => $warehouse) {
                        array_push(
                            $data,
                            [
                                'name' => $warehouse->warehouse[0]->name,
                                'v_date' => $warehouse->v_date,
                                'chalan_no' => $warehouse->chalan_no,
                                'in_qnt' => $warehouse->in_qnt,
                                'out_qnt' => $warehouse->out_qnt,
                                'product_id' => $product->product_id,
                            ]
                        );
                    }
                }
            } else {
                $stock_report_details = WarehouseInserts::with('products', 'warehouse')
                    ->whereBetween('v_date', [$from, $to])
                    ->select("product_id")
                    ->groupBy('product_id')
                    ->get();
                $data = [];
                foreach ($stock_report_details as $product) {
                    foreach ($product->products[0]->warehouse_insert->whereBetween('v_date', [$from, $to]) as $key => $warehouse) {
                        array_push(
                            $data,
                            [
                                'name' => $warehouse->warehouse[0]->name,
                                'v_date' => $warehouse->v_date,
                                'chalan_no' => $warehouse->chalan_no,
                                'in_qnt' => $warehouse->in_qnt,
                                'out_qnt' => $warehouse->out_qnt,
                                'product_id' => $product->product_id,
                            ]
                        );
                    }
                }
            }



            $new_data = collect($data)->groupBy('product_id');


            $company_info = Company::where('id', $this->user->company_id)->first();
            $pdf = PDF::loadView('warehouse::StockReportDetails.printStockReportDetails', compact('company_info', 'stock_report_details', 'pdf_style', 'title', 'from', 'to', 'warehouse_data', 'new_data'));
            $pdf->setPaper('A4', 'potrait');
            $name = "Stock Report Details.pdf";
            return $pdf->stream($name, array("Attachment" => false));
        } else {
            return back()->with('warning', 'Please select from date and to date');
        }
    }


    //unapprove request
    public function unapprove_request($id)
    {
        $sell_request = SellRequest::where("id", $id)->first();

        if ($sell_request && auth('web')->user()->role->id == 9) {
            $sell_request->is_approved = false;
            if ($sell_request->save()) {
                return back()->with('success', 'Sell Request Unapproved.');
            }
        } else {
            return back()->with('error', 'Invalid Sell Request');
        }
    }
}
