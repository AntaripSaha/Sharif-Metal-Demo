<?php

namespace Modules\Seller\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SellRequest extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['req_id','seller_id', 'customer_id', 'amount', 'is_approved'];
    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     return $eventName." Product's ";
    // }
    protected static $logName = "Sell Request";

    protected $fillable = ['seller_id','customer_id','req_id','v_date','del_date','company_id','amount','del_amount','is_delivered','is_approved','approved_by','voucher_no','remarks','gift','discount','del_discount','sale_disc','pname','dco_code','po_code','receiver','phn_no','project_address','transp_name','deliv_pname','fully_delivered','due_amount'];

    public function customer()
    {
        return $this->belongsTo('\Modules\Customer\Entities\Customer','customer_id','id');
    }

    public function company()
    {
        return $this->belongsTo('\Modules\Company\Entities\Company','company_id','id');
    }

    public function seller()
    {
        return $this->belongsTo('\App\User','seller_id','id');
    }

    public static function createRequest($requestData){
        try{
            $id = static::create($requestData);
            return $id->id;

        }catch(\Exception $e){

            throw new \Exception($e->getMessage(), 1);
        }
    }
}
