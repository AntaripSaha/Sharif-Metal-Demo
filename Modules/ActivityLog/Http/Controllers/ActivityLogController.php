<?php

namespace Modules\ActivityLog\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(){
        $today = Carbon::now()->toDateString();
        $activityLogs = Activity::orderBy('id', 'DESC')->where('updated_at', 'like', $today.'%')->paginate(50);
        return view('activitylog::index', compact('activityLogs'));
    }
}
