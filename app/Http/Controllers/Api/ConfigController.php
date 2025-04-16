<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Classroom;
use App\Models\Setting;
use Response;

class ConfigController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::orderBy('sort_number', 'asc')->get();
        $centers = Center::all();
        $setting = Setting::first();
        return Response::api(200, ['classrooms'=>$classrooms,'centers'=>$centers,'setting'=>$setting],'تم جلب البيانات بنجاح');
    }
}
