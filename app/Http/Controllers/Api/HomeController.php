<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Lesson;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index(){

        $user = auth('api')->user();


        // $lessons = Lesson::with([
        //     'files',
        //     'videos' => function ($query) {
        //         $query->select(['id','name','sort_number','views_count','duration','is_active','description','link_type','is_free','price','created_at','lesson_id'])->where('is_active', 1);
        //     },
        //     'required_exam' => function ($query) {
        //         $query->where('status', '!=', 'pending')
        //         ->where('status','!=', 'show_results');
        //     },
        //     'exams' => function ($query) {
        //         $query->where('status', '!=', 'pending')
        //         ->where('status','!=', 'show_results');
        //     },
        // ])
        // ->where('is_active', operator: 1)
        // ->where('classroom_id', $user->classroom_id)
        // ->get();

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
        $systems = System::where('is_active', operator: 1)->where('classroom_id', $user->classroom_id)->get();

        return Response::api(200, [
            'banners' => $banners,
            'lessons' => $lessons,
            'systems'=> $systems
        ],'تم جلب البيانات بنجاح');


    }
}
