<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivateSystemRequest;
use App\Models\Code;
use App\Models\Student;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SystemsController extends Controller
{


    public function show(Request $request)
    {
        $system = System::with('lessons')->find($request->system_id);
        return Response::api(200, $system , 'تم جلب البيانات بنجاح');
    }


    public function activateSystem(ActivateSystemRequest $request){


        $user_id = auth('api')->user()->id;
        $user = Student::find($user_id);

        if($user->systems()->where('system_id', $request->system_id)->exists()){
            return Response::api(400, null , 'هذا النظام مفعل لديك مسبقا');
        }

        $system = System::find($request->system_id);
        $code = Code::where('code', $request->code)->first();
        
        if ($code->is_used) {
            return Response::api(400, null , 'هذا الكود تم استخدامه من قبل');
        }

        if((float)$code->price !== (float)$system->price){
            return Response::api(400, null , 'سعر الكود لا يتناسب مع سعر النظام');
        }

        $user->systems()->attach($system->id, [
            'activated_with' => 'code',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $code->update([
            'system_id'=> $system->id,
            'student_id'=> $user->id,
            'is_used'=> 1,
            'used_at'=> now(),
        ]);

        return Response::api(200, $system , 'تم تفعيل النظام بنجاح');


    }
}
