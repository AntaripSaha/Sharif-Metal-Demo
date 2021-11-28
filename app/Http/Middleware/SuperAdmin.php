<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin {
    
    public function handle($request, Closure $next){  
        
        if (!Auth::user()->isSuperAdmin()) {
           \App::abort(401, 'Not authenticated');
        }
        return $next($request);
    }
}
