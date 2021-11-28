<?php

namespace App\Http\Controllers;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*use App\Models\Setting;*/
use App\Models\Country;


class BaseController extends Controller
{

    Protected $user;
    Protected $request;
    
    public function __construct()
    {   
        $this->middleware(function ($request, $next) {
            $this->user= Auth::User();
            $this->request=$request;
            return $next($request);
        });
               
    }

    function ajaxGetCountryCode(Request $request,$country_code){

        $country_code=Country::whereId($country_code)->value('country_code');
        echo json_encode(array('country_code'=>$country_code));
        
    }
     
}