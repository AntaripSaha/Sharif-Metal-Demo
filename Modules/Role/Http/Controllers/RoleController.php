<?php

namespace Modules\Role\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Role\Entities\Role;
use App\Models\Permission;
use App\Models\Module;
use App\User;
use DB;
use DataTables;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct()
    {
        parent::__construct(); 
    }

    public function index(Request $request){ 
        
        if(!$this->user->can('browse',app('Modules\Role\Entities\Role'))){
            return redirect()->route('users.dashboard')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 

        $data=Role::getRoleList($this->user,$this->user->company_id);
        
        if ($request->ajax()) {          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btnDel ="";
                    $btn_view ='';
                    $btn_edit ='';
                    if($this->user->isOfficeAdmin() ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                        $btn_edit ='<a class="mr-2 btn btn-info btn-sm cp edit-tr" id="edit-tr-'.$row->id.'">Edit</a>';
                        $btnDel = '<a class="btn btn-danger delete-tr btn-sm" id="delete-tr-'.$row->id.'">Delete</a>';
                    }
                    if($this->user->can('view',app('Modules\Role\Entities\Role')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                    }
                    if($this->user->can('edit',app('Modules\Role\Entities\Role')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> Edit</a>'; 
                    }
                    if($this->user->can('delete',app('Modules\Role\Entities\Role'))){
                        $btnDel ='<a class="mr-2 cp delete-tr btn btn-danger btn-sm" id="delete-tr-'.$row->id.'"> Delete</a>'; 
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
        return view('role::index') ;
        
    }

    public function addRole(Request $request){

        if(!$this->user->can('add',app('Modules\Role\Entities\Role'))){
            return response()->json(['status'=>'permission denied'], 401);
        }    
        if($request->isMethod('post')){
            try{  
                $data=$request->all();              
                $data['created_by']=$request->user()->id;
                $data['company_id']=$this->user->company_id;
                if ($this->user->isOfficeAdmin()) {
                    $data['parent_id'] = Role::companyAdminRoleId($this->user->company_id);
                    $is_admin = 1;
                }else{
                    $data['parent_id'] = $this->user->role_id;
                    $is_admin = 0;
                }
                
                $role = Role::createRole($data);
                return response()->json(['status'=>'success','role'=>$role,'is_admin'=>$is_admin], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   

        }else{        
            return view('role::add');exit;
        }
    }

    public function editRole(Request $request,$id){

        try{
            $role=Role::findOrFail($id);   
            if(!$this->user->can('edit',$role)){
                return response()->json(['status'=>'permission denied'], 401);
            }  
            if($request->isMethod('post')){
                 
                $data=$request->all();              
                $data['updated_by']=$request->user()->id;
                Role::updateRole($data,$id);
                return response()->json(['status'=>'success'], 200);

            }else{  
                    
                return view('role::index',compact('role'));exit;
            }
        }catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()], 500);

        }
    }

    public function deleteRole(Request $request ,$role_id){

        try{
            $role=Role::findOrFail($role_id);

            if(!$this->user->can('delete',$role)){
                return response()->json(['status'=>'permission denied'], 401);
            }  
            if($this->user->company_id != $role->company_id){
                return response()->json(['status'=>'error'], 200);
            }
            if(Role::checkIfUserHasRole($role_id)){
                return response()->json(['status'=>'error'], 200);
            }
            $role->delete();
            $role->permissions()->detach();
            DB::commit();
            return response()->json(['status'=>'success'], 200);

        }catch(\Exception $e){
            return response()->json(['status'=>'error'], 500);
        }   
    }

    public function rolePermissions($role_id){

        $data=$this->request->all();
        $role_id=$role_id;
        $role=Role::find($role_id);
        if(!$this->user->can('view',$role)){
            return "No Permission";
        }  
        if(!$role->isOfficeAdmin()){
            $permissions=$this->user->company->getCompanyModules();
            $role_permission=$role->permissions->pluck('id')->toArray();
            return view('role::role_permissions',compact('role','permissions','role_permission')) ;
        }
    }

    public function saveRolePermissions(Request $request,$role_id){

        try{ 

            $role=Role::findOrFail($role_id);
            if(!$this->user->can('edit',$role)){
                return response()->json(['status'=>'permission denied'], 401);
            }  
            if($this->user->company_id != $role->company_id) {
                return response()->json(['status'=>'permission denied'], 401);
            } 
            DB::beginTransaction();
            $permissions=(isset($request['permissions']))?$request['permissions']:array();
            $role->permissions()->sync($permissions);
            $role->permissions()->detach();
            if($permissions){
                $role->permissions()->attach($permissions);
            }
            DB::commit();
            return response()->json(['status'=>'success'], 200);
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status'=>$e->getMessage()], 500);
        }
    }

}
