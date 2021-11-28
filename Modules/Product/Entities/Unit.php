<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Unit extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['unit_name', 'status'];
    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     return $eventName." Product Unit ";
    // }
    protected static $logName = "Product Unit";

    protected $fillable = ['unit_name','company_id','status','created_at','updated_at'];
    
    public static function createUnit($requestData){
        try{
            static::create($requestData);

        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
    public static function updateUnit($requestData, $unit_id){
        try{
            $unit = static::find($unit_id);
            $unit->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }
}
