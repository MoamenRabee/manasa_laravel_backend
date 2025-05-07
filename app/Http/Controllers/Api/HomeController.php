<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivateCodeRequest;
use App\Models\Banner;
use App\Models\Code;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\System;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index()
    {

        $user = auth('api')->user();

        $lessons = Lesson::with([
            'required_exam' => function ($query) {
                $query->where('status', '!=', 'pending')
                    ->where('status', '!=', 'show_results');
            },
        ])
            ->where('is_active', operator: 1)
            ->where('classroom_id', $user->classroom_id)
            ->get();

        $banners = Banner::all();
        $monthlySystems = System::where('is_active', operator: 1)->where('classroom_id', $user->classroom_id)->where('type', 'month')->get();
        $packagesSystems = System::with('lessons')->where('is_active', operator: 1)->where('classroom_id', $user->classroom_id)->where('type', 'package')->get();

        return Response::api(200, [
            'banners' => $banners,
            'lessons' => $lessons,
            'monthlySystems' => $monthlySystems,
            'packagesSystems' => $packagesSystems,
        ], 'تم جلب البيانات بنجاح');


    }


    public function activateCode(ActivateCodeRequest $request)
    {

        $user_id = auth('api')->user()->id;
        $user = Student::find($user_id);

        $type = $request->type;

        // if ($type != 'system' && $type != 'lesson')
        //     return Response::api(400, null, 'النوع غير صحيح');

        if ($type === 'system') {
            if ($user->systems()->where('system_id', $request->id)->exists()) {
                return Response::api(400, null, 'هذا النظام مفعل لديك مسبقا');
            }
        } else if ($type === 'lesson') {
            if ($user->lessons()->where('lesson_id', $request->id)->exists()) {
                return Response::api(400, null, 'هذا الدرس مفعل لديك مسبقا');
            }
        }

        if ($type == 'system') {
            $system = System::find($request->id);
            $code = Code::where('code', $request->code)->first();

            if ($code->is_used) {
                return Response::api(400, null, 'هذا الكود تم استخدامه من قبل');
            }

            if ((float) $code->price !== (float) $system->price) {
                return Response::api(400, null, 'سعر الكود لا يتناسب مع سعر النظام');
            }

            $user->systems()->attach($system->id, [
                'activated_with' => 'code',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $code->update([
                'system_id' => $system->id,
                'student_id' => $user->id,
                'is_used' => 1,
                'used_at' => now(),
            ]);
        } else if ($type == 'lesson') {


            $lesson = Lesson::find($request->id);
            $code = Code::where('code', $request->code)->first();

            if ($code->is_used) {
                return Response::api(400, null, 'هذا الكود تم استخدامه من قبل');
            }

            if ((float) $code->price !== (float) $lesson->price) {
                return Response::api(400, null, 'سعر الكود لا يتناسب مع سعر الدرس');
            }

            $user->lessons()->attach($lesson->id, [
                'activated_with' => 'code',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $code->update([
                'lesson_id' => $lesson->id,
                'student_id' => $user->id,
                'is_used' => 1,
                'used_at' => now(),
            ]);

        }


        return Response::api(200, ['type'=>$type,'data'=>$type === 'system' ? $system : $lesson], 'تم التفعيل بنجاح');

    }

}
