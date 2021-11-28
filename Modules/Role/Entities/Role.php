<?php

namespace Modules\Role\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class Role extends Model
{
    protected $fillable=['name','display_name','company_id','parent_id','created_by','updated_by','created_at','updated_at'];
    
    protected static $officeAdmin=1;

    public function isOfficeAdmin(){

        return $this->parent_id==self::$officeAdmin;
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission');
    }

    public static function companyAdminRoleId($company_id){

        return static::whereCompanyId($company_id)->whereParentId(self::$officeAdmin)->first()->id;
    }


    public static function companyRoleList($company_id){

        $roles = static::whereCompanyId($company_id)->get();

/*        foreach($roles as $role){
            if($role->name == 'Company Admin'){
                $role->name = __('roles.organization_admin');
            }
        }*/

        return $roles;
    }

    public static function getRoleList($user,$company_id=null){

        $roles=array();
        if($user->isSuperAdmin()  && $company_id){
            $roles=static::whereCompanyId($company_id)->get();
        }
        elseif($user->isOfficeAdmin()){
            $roles=static::whereNotNull('parent_id')->where('parent_id','<>',self::$officeAdmin)->whereCompanyId($user->company_id)->get();
        }else{
            $roles=static::whereNotNull('parent_id')->where('parent_id','!=',self::$officeAdmin)->where('id','!=',$user->role_id)->whereCompanyId($user->company_id)->get();
        }

        return $roles;

    } 


    public static function createRole($requestData){
        
        try{
            $roleid = static::create($requestData);
            return $roleid->id;        
        }catch(\Exception $e){   
            throw new \Exception($e->getMessage(), 1);               
        }
    }


    public static function updateRole($requestData,$role_id){

        try{
            $role=static::findOrFail($role_id);
            $role->fill($requestData)->save();
        }catch(\Exception $e){   
            throw new \Exception($e->getMessage(), 1);           
        }
    }


    public static function checkIfUserHasRole($role_id){
        $data=User::whereRoleId($role_id)->first();
        return ($data)?true:false;
    } 



}
