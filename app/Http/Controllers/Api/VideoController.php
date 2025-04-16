<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VideoController extends Controller
{
    public function index(Request $request){
        $videoId = $request->video_id;
        $video = Video::find( $videoId);
        if($video === null) return Response::api(404, $video , 'لا يوجد فيديو بهذا المعرف');

        $user_id = auth('api')->user()->id;
        $user = Student::find($user_id);

        $activated_lesson = $user->lessons()->where('lesson_id', $video->lesson_id)->first();
        $activated_system = $user->systems()->whereHas('lessons', function ($query) use ($video) {
            $query->where('lesson_id', $video->lesson_id);
        })->first();

        if($activated_system !== null || $activated_lesson !== null || $video->is_free == 1 || $video->lesson->is_free == 1){
            return Response::api(200, $video , 'تم جلب البيانات بنجاح');
        }else{
            return Response::api(400, null , 'يرجي التفعيل اولاً');
        }
    }
}
