<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Role\Entities\Role;
use App\Models\Module;
use App\User;
use Illuminate\Support\Facades\Hash;

class Company extends Model
{

    
    protected $fillable=['name','company_no','status','phone_code','phone_no','address','city','postal_code','logo','logo_sm','country_id','parent_id','created_by','updated_by'];

    protected static $rootCompany=1;

    public function country(){

        return $this->belongsTo('\App\Models\Country');
    }

    public function modules(){

        return $this->belongsToMany('App\Models\Module','company_modules','company_id','module_id');
    }
    public function banks()
    {
        return $this->hasMany('\Modules\Bank\Entities\Bank','company_id','id');        
    }
    public function isRootCompany(){

        return ($this->id==self::$rootCompany);
    }

    public static function getCompanies(){
        return static::with('country')->orderBy('id')
                    ->get()->map(function($item,$key){
                        $item->country_name=__('countries.'.$item->country->name);
                        return $item;
                    });   
    }

    public static function getCompanyById($company_id){
        return static::where('id',$company_id)->first();
    }
    public static function getCountryById($country_id, $translated = true){
        # if translated = true
        # display with translated country

        if($translated){
            $country = Country::whereId($country_id)->with('country')->first();
            $country->country->name = __('countries.'.$organization->country->name);
        }else{
            $country = Country::whereId($country_id)->with('country')->first();
        }

        return $country;
    }

    public static function createCompany($requestData){
        try{
            $requestData['status']='1';
            $requestData['parent_id']=1; 

            $row=static::create($requestData);

            $roleData=array();
            $roleData['name']='Company Admin';
            $roleData['parent_id']='1';
            $roleData['company_id']=$row->id;
            $roleData['created_by']=$requestData['created_by'];
            $role = Role::create($roleData);

            $userdata = array();
            $userdata['name']=$requestData['name'].' Admin';
            $userdata['email']='admin@'.$requestData['phone_no'].'.com';
            $userdata['parent_id']='1';
            $userdata['country_id']=$requestData['country_id'];
            $userdata['role_id']=$role->id;
            $userdata['company_id']=$row->id;
            $userdata['created_by']=$requestData['created_by'];
            $userdata['password']=Hash::make('123456');
            $userdata['status']='1';

            User::create($userdata);

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               

        }
    }

      public static function requestCompany($requestData){
        try{
            $requestData['status']='1';
            $requestData['parent_id']=1; 
            $requestData['created_by']=1;
            $row=static::create($requestData);

            $roleData=array();
            $roleData['name']='Company Admin';
            $roleData['parent_id']='1';
            $roleData['company_id']=$row->id;
            $roleData['created_by']=$requestData['created_by'];
            $role = Role::create($roleData);
            $data['company_id'] = $row->id;
            $data['role_id'] = $role->id;
            return $data;

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               

        }
    }

    public static function updateCompany($requestData,$company_id){
        try{

            $company=static::find($company_id);
            $company->fill($requestData)->save();

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               

        }
    }

    public  function getCompanyModules(){

        $modules=$this->modules()->get();
        return $modules;
    }

    public function getCompanyModulePermissions(){

         return $this->modules->pluck('id')->toArray();
    }


}