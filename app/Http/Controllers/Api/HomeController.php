<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Lesson;
use App\Models\System;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index(){

        $user = auth('api')->user();

        $lessons = Lesson::with([
            'required_exam' => function ($query) {
                $query->where('status', '!=', 'pending')
                ->where('status','!=', 'show_results');
            },
        ])
        ->where('is_active', operator: 1)
        ->where('classroom_id', $user->classroom_id)
        ->get();

        $banners = Banner::all();
        $monthlySystems = System::where('is_active', operator: 1)->where('classroom_id', $user->classroom_id)->where('type','month')->get();
        $packagesSystems = System::where('is_active', operator: 1)->where('classroom_id', $user->classroom_id)->where('type','package')->get();

        return Response::api(200, [
            'banners' => $banners,
            'lessons' => $lessons,
            'monthlySystems'=> $monthlySystems,
            'packagesSystems'=> $packagesSystems,
        ],'تم جلب البيانات بنجاح');


    }
}
