<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;

class WarehouseProducts extends Model
{
    protected $fillable = ['product_id','warehouse_id','company_id','created_by','stck_q','sell_q','head_code'];

    public function warehouse()
    {
        return $this->hasMany('\Modules\Warehouse\Entities\Warehouse','id','warehouse_id');        
    }
    public function products()
    {
        return $this->hasMany('\Modules\Product\Entities\Product','id','product_id');        
    }
    
    public function product()
    {
        return $this->belongsTo('\Modules\Product\Entities\Product');        
    }
    
    public static function insert_product($requestData)
    {
        try{
            static::create($requestData);
        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
}
