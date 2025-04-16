<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function index(Request $request,string $id){
        $file = File::find( $id);
        if($file === null) return Response::api(404, $file , 'لا يوجد فيديو بهذا المعرف');

        $user_id = auth('api')->user()->id;
        $user = Student::find($user_id);


        $activated_lesson = $user->lessons()->where('lesson_id', $file->lesson_id)->first();
        $activated_system = $user->systems()->whereHas('lessons', function ($query) use ($file) {
            $query->where('lesson_id', $file->lesson_id);
        })->first();

        if($activated_system !== null || $activated_lesson !== null || $file->lesson->is_free == 1){
            return Response::api(200, $file , 'تم جلب البيانات بنجاح');
        }else{
            return Response::api(400, null , 'يرجي التفعيل اولاً');
        }
    }
}
