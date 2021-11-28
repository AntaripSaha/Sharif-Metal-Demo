<?php

namespace Modules\User\Http\Controllers;

use App\Exports\CustomerExport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController;
use App\Imports\SellerUserImport;
use Auth;
// use DB;
// use DataTables;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Company\Entities\Company;
use Modules\Role\Entities\Role;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Customer\Entities\Customer;
use Modules\Seller\Entities\RequestProduct;
use Modules\Supplier\Entities\Supplier;

class UserController extends BaseController
{
    // Dashboard Function
    public function index()
    {
//        return view('errors.under_construction');

        $totalCompany = Company::count('id');
        $totalUsers = User::count('id');
        $totalWarehouses = Warehouse::count('id');
        $totalActiveUsers = User::where('status',1)->count('id');
        $totalInactiveUsers = User::where('status',0)->count('id');
        $totalCustomers = Customer::count('id');
        $totalSuppliers = Supplier::count('id');

        //View Seller Dashboard Component. Seller Role ID = 4
        if(auth()->user()->role_id == 4){
            return view('user::seller_dashboard');
        }else{
            return view('user::index', compact('totalCompany','totalUsers','totalWarehouses',
                                            'totalActiveUsers','totalInactiveUsers',
                                            'totalCustomers', 'totalSuppliers'
                                            ));
        }
    }

    public function getProductRequestChart(Request $request){
        if($request->ajax()){
            $productRequestChart = RequestProduct::orderBy("id","desc")->with('products')->selectRaw('product_id, AVG(qnty) qnty')
                                    ->groupBy('product_id')
                                    ->take(20)
                                    ->get();

            return response()->json(['productRequestChart' => $productRequestChart], 200);
        }
    }

    public function user_list(Request $request){
        if(!$this->user->can('browse',app('App\User'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        $data = User::with('role')->where('company_id',$this->user->company_id)->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = "";
//                    $btnDel ="";
//                    $btn_view ='';
                    $btn_edit ='';
                    $btn_changePassword = '';
                    if($this->user->isOfficeAdmin()){
                        // $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>';
//                        $btn_edit ='<a class="mr-2 btn btn-info btn-sm cp edit-tr" id="edit-tr-'.$row->id.'">Edit</a>';
//                        $btnDel = '<a class="btn btn-danger delete-tr btn-sm" id="delete-tr-'.$row->id.'">Delete</a>';
                        // $btn_changePassword = '<a class="cp changePassword-tr btn btn-warning btn-sm" id="changePassword-tr-'.$row->id.'"> <i class="fas fa-key"></i> </a>';
                    }
                    if($this->user->can('view',app('App\User')) ){
//                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> <i class="fas fa-eye"></i> </a>';
                    }
                    if($this->user->can('edit',app('App\User')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> <i class="fas fa-edit"></i> </a>';
                        $btn_changePassword = '<a class="cp changePassword-tr btn btn-warning btn-sm " href="'.route('change_user_password',$row->id).'">  <i class="fas fa-key"></i> </a>';
                    }
                    if($this->user->can('delete',app('App\User'))){
//                        $btnDel ='<a class="mr-2 cp delete-tr btn btn-danger btn-sm" id="delete-tr-'.$row->id.'"> <i class="far fa-trash-alt"></i> </a>';
                    }
                    if($this->user->can('edit', app('App\User'))){
                        
                    }
//                    return $btn_view.$btn_edit.$btnDel.$btn_changePassword;
                    return $btn_edit.$btn_changePassword;
                })
                ->editColumn('status',function($row){
                    if ($row->status == 1) {
                        $status = 'Active';
                    }else{
                        $status = 'Inactive';
                    }
                    return $status;
                })
                ->addColumn('input',function($row){
                    $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                    return $input;
                })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        return view('user::user_list');
    }

    public function add_user(Request $request)
    {
        if(!$this->user->can('add',app('App\User'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                $data['company_id']=$request->user()->company_id;
                $data['created_by'] = $request->user()->id;
                $pass = Hash::make($request->password);
                $data['password'] = $pass;
                $data['country_id'] = 1;
                if (!$request['user_id']) {
                    $data['user_id'] = null;
                }
                if(!$request['parent_id']){
                    $data['parent_id'] = null;
                }
                User::createUser($data);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $users = User::all();
            $user_roles = Role::where('parent_id', '!=', null)->where('parent_id', '!=', 1)->orwhere('id',4)->get();
            return view('user::add_user', compact('user_roles', 'users'));exit;
        }
    }

    public function view_user($id)
    {
        if(!$this->user->can('view',app('App\User'))){
            return response()->json(['status'=>'permission denied'], 401);
        }
        $user = User::with('role')->where('id', $id)->first();
        $parent_info = User::where('id', $user->parent_id)->select('name')->first();
        return view('user::view_user', compact('user', 'parent_info'));
    }

    public function edit_user(Request $request,$id)
    {
        if(!$this->user->can('edit',app('App\User'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        if($request->isMethod('post')){
            try{
                $data=$request->all();
                $data['company_id']=$request->user()->company_id;
                $data['created_by'] = $request->user()->id;
                $data['country_id'] = 1;

                User::updateUser($data,$id);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            $user = User::with('role')->where('id', $id)->first();
            // dd($user);exit();
            $user_roles = Role::where('parent_id', '!=', null)->where('parent_id', '!=', 1)->get();
            return view('user::edit_user',compact('user','user_roles'));exit;
        }
    }

    public function deleteUser(Request $request,$id){
        if(!$this->user->can('delete',app('App\User'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        try{
            $user = User::where('id',$id)->first();
            $delete_user = DB::table('users')->where('id',$id)->update(['status' => 0]);
            return response()->json(['status' => 'success'],200);
        }catch(\Exception $e){
            return response()->json(['status'=>'error'], 500);
        }
    }


    // Import Sellers Excel File Start
    public function importSellers(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'sellers_file' => 'required',
                ]);
            try{
                $files = $request->file('sellers_file');
                $import = Excel::import(new SellerUserImport, $files);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){

                return response()->json(['status'=>$e->getMessage()], 500);
            }
        }else{
            return view('user::import_sellers');exit;
        }
    }
    // Import Sellers Excel File End

    // User Password Change Start
    public function changeUserPasswordPage($id){
        $id = User::where("id",$id)->first()->id;
        return view('user::change_password', compact('id'));
    }

    public function changePasswordPost($id, Request $request){
    
        $request->validate([
            "old_password" => "required",
            "password" => "required|confirmed|min:6",
        ]);
    
        $user = User::where("id",$id)->first();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                $request->session()->flash("success","Password Updated");
                return redirect()->route('change_user_password', $id);
            }
        } else {
            $request->session()->flash("error","Password Not Match");
            return redirect()->route('change_user_password', $id);

        }
    }
    // User Password Change End

}
