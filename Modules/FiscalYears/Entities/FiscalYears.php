<?php

namespace Modules\FiscalYears\Entities;

use Illuminate\Database\Eloquent\Model;

class FiscalYears extends Model
{
    protected $fillable = ['starting_date', 'ending_date', 'status'];


    public static function createFiscalYear($requestData){
        try{
            $id = static::create($requestData);
            
            return $id;
        }catch(\Exception $e){   

            throw new \Exception($e->getMessage(), 1);               
        }
    }

    public static function updateFiscalYear($requestData, $bank_id){
        try{
            $bank = static::find($bank_id);
            $bank->fill($requestData)->save();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage(), 1);  
        }
    }
}
