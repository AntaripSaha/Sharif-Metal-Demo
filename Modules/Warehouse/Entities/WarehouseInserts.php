<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class WarehouseInserts extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['chalan_no', 'v_date', 'del_date' ,'in_qnt', 'out_qnt', 'production_price'];
    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     return $eventName." Product's ";
    // }
    protected static $logName = "Warehouse Product Insert";

    protected $fillable = ['product_id','warehouse_id','company_id','chalan_no','in_qnt','v_date','created_by','del_date','out_qnt'];

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
        return $this->belongsTo('\Modules\Product\Entities\Product','product_id','id');        
    }
    public static function insertware_product($requestData){
        try{
            static::create($requestData);

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
}
