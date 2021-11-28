<?php

namespace Modules\Company\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;
use Auth;
use Modules\Company\Entities\Company;
use App\Models\Country;
use App\Http\Controllers\BaseController;
class CompanyController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data = Company::where('parent_id',1)->get();
        if ($request->ajax()) {          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btnDel ="";
                    $btn_view ='';
                    $btn_edit ='';
                    if($row->created_by == $this->user->id ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                        $btn_edit ='<a class="mr-2 btn btn-info btn-sm cp edit-tr" id="edit-tr-'.$row->id.'">Edit</a>';
                        $btnDel = '<a class="btn btn-danger delete-tr btn-sm" id="delete-tr-'.$row->id.'">Delete</a>';
                    }
                    return $btn_view.$btn_edit.$btnDel;
                        
                            
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
        return view('company::index',compact('total'));
    }

    public function addCompany(Request $request){

        if($request->isMethod('post')){
            try{  
                $data=$request->all();
                
                $data['created_by']=$request->user()->id;
                Company::createCompany($data);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            $country=Country::countriesTranslated();
            return view('company::add',compact('country'));exit;
        }
    }

    public function viewCompany(Request $request ,$company_id){

        $company=Company::getCompanyById($company_id);
        return view('company::company_details',compact('company')) ;
    }

    public function editCompany(UpdateRequest $request,$company_id){

        if($request->isMethod('post')){

            try{  
                $data=$request->all();              
                $data['updated_by']=$request->user()->id;
                company::updateCompany($data,$company_id);
                return response()->json(['status'=>'success'], 200);

            }catch(\Exception $e){

                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            try{

                $Company=Company::findOrFail($company_id);
                $country=Country::countriesTranslated();
                return view('Company::edit',compact('country','Company'));exit;

            }catch(\Exception $e){

                return response()->json(['status'=>__('Companys.no_Company_found')], 500);
            }
        }
    }

    function deleteCompany(Request $request,$company_id){

        try{
            $company=Company::findOrFail($company_id);
            if($company->isRootCompany()){
                return response()->json(['status'=>'error'], 200);
            }
            $company->delete();
            return response()->json(['status'=>'success'], 200);

        }catch(\Exception $e){
            return response()->json(['status'=>'error'], 500);
        }   
    }

    public function settings(){
        return view('company::settings.index');
    }
}
