<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function getQuestions(Request $request,string $id){
        $exam = Exam::with([
            'questions' => function ($query) {
                $query->select(['id','question','image','type','exam_id','options']);
            },
            ])->find($id);

        return response()->json([
            'status' => 200,
            'data' => $exam,
            'message' => 'تم جلب البيانات بنجاح'
        ]);
    }
}
