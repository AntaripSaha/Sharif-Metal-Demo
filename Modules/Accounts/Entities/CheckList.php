<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    protected $fillable = ['check_no','VDate','mat_date','is_credited','COAID','customer_id','company_id','bank_name'];

    public function customer()
    {
        return $this->belongsTo('\Modules\Customer\Entities\Customer','customer_id','id');        
    }
    public function company()
    {
        return $this->belongsTo('\Modules\Company\Entities\Company','company_id','id');        
    }
    public static function add_check($requestData){
        try{
            static::create($requestData);
        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
}
