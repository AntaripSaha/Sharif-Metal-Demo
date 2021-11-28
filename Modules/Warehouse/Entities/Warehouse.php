<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Warehouse extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name', 'location', 'status'];
    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     return $eventName." Product's ";
    // }
    protected static $logName = "WareHouse";

    protected $fillable = ['name','location','company_id','status','created_at','updated_at'];

    public function products()
    {
        return $this->hasMany('\Modules\Product\Entities\Product','id','warehouse_id');        
    }
    public static function createWarehouse($requestData){
        try{
            static::create($requestData);

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
    public static function updateWarehouse($requestData, $warehouse_id){
        try{
            $warehouse = static::find($warehouse_id);
            $warehouse->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }
}
