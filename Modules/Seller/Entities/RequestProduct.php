<?php

namespace Modules\Seller\Entities;

use Illuminate\Database\Eloquent\Model;

class RequestProduct extends Model
{
    protected $fillable = ['product_id','unit_price','production_price','qnty','req_id','del_qnt','prod_disc'];

    public function products()
    {
        return $this->belongsTo('\Modules\Product\Entities\Product','product_id','id');        
    }

    public static function createProductReq($requestData)
    {
        try{
            static::create($requestData);

        }catch(\Exception $e){   
            
            throw new \Exception($e->getMessage(), 1);               
        }
    }
}
