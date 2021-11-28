<?php

namespace Modules\Module\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Module;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;


class ModuleController extends BaseController
{

    public function __construct()
    {
        parent::__construct();  
    }

    public function index(Request $request){ 
      $company = Company::where('parent_id',1)->orderBy('id')->get();
      return view('module::index',compact('company')) ;
        
    }

    public function companyTree(Request $request){
        
        $result=array();
        $data=Company::orderBy('id')->get();
            foreach ($data as  $value) {
                $a=array();
                if(!$value->parent_id){
                  $a['id']=$value->id;
                  $a['text']=$value->name;
                  $a['parent']='#';
                  $a['icon']='fa fa-home';
                  $a['state']=array('disabled'=>true,'opened'=>true);
                }else{
                  $a['id']=$value->id;
                  $a['text']=$value->name;
                  $a['parent']=$value->parent_id;
                  $a['icon']='fa fa-home';
              }
          $result[]=(object)$a;
        }
        echo json_encode($result);
    }

    function modulePermissions($id){
        $company_id=$id;
        $company=Company::find($company_id);
        if(!$company->isRootCompany()){
          $modules=Module::all();
          $modules_permissions=$company->getCompanyModulePermissions();
          return view('module::module_permissions',compact('modules_permissions','modules','company_id')) ;
        }

    }

    public function saveModulePermissions(Request $request,$company_id){

        try{
            $company=Company::findOrFail($company_id);
            $permissions=(isset($request['permissions']))?$request['permissions']:array();
            $company->modules()->sync($permissions);
            $company->modules()->detach();
            if($permissions){
                $company->modules()->attach($permissions);
            }
            DB::commit();
            return response()->json(['status'=>'success'], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }
   
}
