<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['product_name', 'product_model', 'product_details', 'price', 'production_price'];
    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     return $eventName." Product's ";
    // }
    protected static $logName = "Product";
    
    protected $fillable = ['date','product_id','is_head','head_code','product_name','product_model','product_details','image','price','tax','category_id','unit_id','company_id','status','created_at','updated_at','is_set','set_id','combo_ids','production_price','supplier_id'];

    public function category()
    {
        return $this->belongsTo('\Modules\Product\Entities\Category','category_id','id');        
    }
    public function supplier()
    {
        return $this->belongsTo('\Modules\Supplier\Entities\Supplier','supplier_id','id');        
    }
    public function product_set()
    {
        return $this->belongsTo('\Modules\Product\Entities\ProductSet','set_id','set_id');        
    }
    public function warehouse_insert()
    {
        return $this->hasMany('\Modules\Warehouse\Entities\WarehouseInserts','product_id','id');        
    }

    public function company(){
        return $this->hasMany('\Modules\Company\Entities\Company', 'id', 'company_id');
    }
    public static function createProduct($requestData){
        try{
            static::create($requestData);

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
    public static function updateProduct($requestData, $product_id){
        try{
            $unit = static::find($product_id);
            $unit->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }
}
