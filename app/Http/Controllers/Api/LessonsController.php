<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivateLessonRequest;
use App\Models\Code;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LessonsController extends Controller
{
    public function show(Request $request)
    {

        $lesson = Lesson::with([
            'files' => function ($query) {
                $query->select(['id','name','image','lesson_id']);
            },
            'videos' => function ($query) {
                $query->select(['id','name','sort_number','views_count','duration','is_active','description','link_type','is_free','price','created_at','lesson_id'])->where('is_active', 1);
            },
            'required_exam' => function ($query) {
                $query->where('status', '!=', 'pending')
                ->where('status','!=', 'show_results');
            },
            'exams' => function ($query) {
                $query->where('status', '!=', 'pending')
                ->where('status','!=', 'show_results');
            },
        ])->find($request->lesson_id);



        return Response::api(200, $lesson , 'تم جلب البيانات بنجاح');
    }




    public function activateLesson(ActivateLessonRequest $request)
    {
        $user_id = auth('api')->user()->id;
        $user = Student::find($user_id);

        if($user->lessons()->where('lesson_id', $request->lesson_id)->exists()){
            return Response::api(400, null , 'هذا الدرس مفعل لديك مسبقا');
        }

        $lesson = Lesson::find($request->lesson_id);
        $code = Code::where('code', $request->code)->first();
        
        if ($code->is_used) {
            return Response::api(400, null , 'هذا الكود تم استخدامه من قبل');
        }

        if((float)$code->price !== (float)$lesson->price){
            return Response::api(400, null , 'سعر الكود لا يتناسب مع سعر الدرس');
        }

        $user->lessons()->attach($lesson->id, [
            'activated_with' => 'code',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $code->update([
            'lesson_id'=> $lesson->id,
            'student_id'=> $user->id,
            'is_used'=> 1,
            'used_at'=> now(),
        ]);

        return Response::api(200, $lesson , 'تم تفعيل الدرس بنجاح');

    }
}
