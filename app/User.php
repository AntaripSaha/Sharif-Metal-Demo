<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','user_id','parent_id','email', 'password','status','created_at','updated_at','phone_no','address','company_id','role_id','is_manager_seller','created_by','updated_by','country_id','city','postal_code','phone_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static $superAdmin=1;
    protected static $seller=4;

    public function country(){

        return $this->belongsTo('App\Models\Country');
    }

    public function role(){

        return $this->belongsTo('Modules\Role\Entities\Role','role_id');
    }

    public function company(){

        return $this->belongsTo('Modules\Company\Entities\Company','company_id');
    }

    public function customers()
    {
        return $this->hasMany('Modules\Customer\Entities\Customer','seller_id');
    }

    public function isSuperAdmin(){

        return ($this->role_id==self::$superAdmin);
    }

    public function isOfficeAdmin(){

        return $this->role->isOfficeAdmin();
    }

    public function isSeller(){

        return $this->role_id==self::$seller;
    }

    public static function getCompanyUsers($company_id){

        $users=static::whereCompanyId($company_id)->with('country','role')->orderBy('id')
                    ->get()->map(function($item,$key){
                        $item->country_name = $item->country->name;
                        if($item->role->name == 'Company Admin'){
                            $item->role_name = __('roles.organization_admin');
                        }else{
                            $item->role_name = $item->role->name;
                        }

                        return $item;
                    });   
        return $users;
    }


    public static function getUserById($user_id){

        $user=static::whereId($user_id)->with('country','role')->first();

        if($user->role->name == 'Company Admin'){
            $user->role->name = __('roles.organization_admin');
        }

        return $user;
    }


    public static function createUser($requestData){
        
        try{
            $requestData['status']='1';
            $user = static::create($requestData);
            return $user;            
        }catch(\Exception $e){   
            throw new \Exception($e->getMessage(), 1);               
        }
    }

    public static function updateUser($requestData,$user_id){
        try{
            $user=static::find($user_id);
            $user->fill($requestData)->save();
        }catch(\Exception $e){   
            throw new \Exception($e->getMessage(), 1);           
        }
    }



   public function  UserRolesPermissionKey(){
        $permission_array=array();
        $permissions=$this->role()->with(['permissions'=>function($query){
                    $query->pluck('key');
                }])->get()->toArray();  
        foreach ($permissions as $key => $value) {
            foreach ($value['permissions'] as $k => $v) {
                $permission_array[]=$v['key'];
            }
        }
        return $permission_array;
    }


    public function hasPermission($name,$module_id)
    {
        $userRolesPermission=$this->UserRolesPermissionKey() ;
        return ((in_array($name,$userRolesPermission) && in_array($module_id,$this->userCompanyModules()))|| ($this->isOfficeAdmin() && in_array($module_id,$this->userCompanyModules()) ));
    }    


    public function userCompanyModules(){
        if ($this->company == null) {
            $modulePermissions=['1'=>7];
            return $modulePermissions;
        }else{
            $modulePermissions=array();
            $modulePermissions=$this->company->modules()->get()->map(function($item,$key){
                        $permission=$item->pivot->module_id;
                            return $permission;
                    })->toArray();
            return $modulePermissions;
        }
    }


}
