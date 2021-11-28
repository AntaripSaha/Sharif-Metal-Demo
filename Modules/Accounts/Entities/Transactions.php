<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = ['VNo','Vtype','VDate','COAID','Narration','Debit','Credit','IsPosted','company_id','created_by','updated_by','IsAppove','created_at','updated_at'];
    
    public function coa()
    {
        return $this->belongsTo('\Modules\Accounts\Entities\Accounts','COAID','HeadCode');        
    }
    public function company()
    {
        return $this->belongsTo('\Modules\Company\Entities\Company','company_id','id');        
    }
    public static function createTransaction($requestData){
        try{
            $id = static::create($requestData);
            return $id;
        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
}
