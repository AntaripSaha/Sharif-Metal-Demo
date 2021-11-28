<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductSet extends Model
{
    protected $fillable = ['set_id','product_id'];

    public function product()
    {
        return $this->hasMany('\Modules\Product\Entities\Product','set_id','id');        
    }
    public static function createSet($requestData){
        try{
            static::create($requestData);

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
}
