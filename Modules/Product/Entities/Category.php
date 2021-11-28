<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['category_name', 'category_slug', 'status'];
    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     return $eventName." Product Category ";
    // }
    protected static $logName = "Product Category";


    protected $fillable = ['category_name','category_slug','status','company_id','created_at','updated_at'];

    
    public static function createCategory($requestData){
        try{
            static::create($requestData);
        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }
    public static function updateCategory($requestData, $category_id){
        try{
            $unit = static::find($category_id);
            $unit->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }
}
