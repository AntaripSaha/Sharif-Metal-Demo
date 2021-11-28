<?php

namespace Modules\FiscalYears\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use DB;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use Modules\Accounts\Entities\Transactions;
use Modules\FiscalYears\Entities\FiscalYears;

class FiscalYearsController extends BaseController
{
    public function index(Request $request)
    {
        if(!$this->user->can('browse',app('Modules\FiscalYears\Entities\FiscalYears'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        $company_id = Auth::user()->company_id;
        $data = FiscalYears::all();

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
                    if($this->user->can('view_fiscalyears',app('Modules\FiscalYears\Entities\FiscalYears')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                    }
                    if($this->user->can('edit_fiscalyears',app('Modules\FiscalYears\Entities\FiscalYears')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> Edit</a>'; 
                    }
                    if($this->user->can('delete_fiscalyears',app('Modules\FiscalYears\Entities\FiscalYears')) ){
                        $btnDel ='<a class="mr-2 cp delete-tr btn btn-danger btn-sm" id="delete-tr-'.$row->id.'"> Delete</a>'; 
                    }
                    return $btn_view.$btn_edit.$btnDel;   
                    })
                    
                    ->editColumn('status', function ($row) {
                        if ($row->status == true) {
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
        return view('fiscalyears::index');
    }

    // Add New Fiscal Years
    public function add(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            FiscalYears::createFiscalYear($data);
            return response()->json(['status' => 'success'],200);
        }else{
            return view('fiscalyears::add');
        }
    }
}
