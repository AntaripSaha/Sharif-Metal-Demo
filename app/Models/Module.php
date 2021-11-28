<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Module extends Model
{

    protected $table = 'modules';

    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
        'policy_name',
        'controller',
        'description',
        'generate_permissions'
    ];


    public function permissions(){

        return $this->hasMany('App\Models\Permission');
    }

    public static function getPermissions(){

        return static::with('permissions')->get();
    }

    public function createdBy(){

        return $this->belongsTo('\App\User','created_by');
    }
   
}
