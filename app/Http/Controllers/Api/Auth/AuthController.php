<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Auth\ApiEditProfile;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Http\Requests\Api\Auth\ApiRegisterRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Helpers\ImageHelper;



class AuthController extends Controller 
{
    public function login(ApiLoginRequest $request)
    {
        $token =  auth('api')->attempt(['phone' => $request->phone, 'password' => $request->password]);
        if(!$token){
            return Response::api(401,null,'غير مصرح لك');
        }
        
        $user = Student::with('center')->with(relations: 'classroom')->find(auth('api')->user()->id);

        if($user->device_id !== $request->device_id && $user->device_id !== null && $user->device_id !== ''){
            return Response::api(401,null,'الجهاز غير مصرح له بالدخول');
        }

        if ($request->has('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
            $user->device_id = $request->device_id;
            $user->save();
        }

        return Response::api(200,['user'=>$user,'token'=>$token],'تم تسجيل الدخول بنجاح');
    }


    public function logout(Request $request)
    {
        
        if($request->headers->get('authorization') === null){
            return Response::api(401,null,'غير مصرح لك');
        }
        auth('api')->logout();
        return Response::api(200,null,'تم تسجيل الخروج بنجاح');
    }


    public function profile(Request $request)
    {
        $userLogin = auth('api')->user();
        $user = Student::with('lessons')->with('systems')->with('center')->with('classroom')->find($userLogin->id);
        return Response::api(200,$user,'تم جلب البيانات بنجاح');
    }


    public function register(ApiRegisterRequest $request){

        Student::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'parent_phone' => $request->parent_phone,
            'password' => bcrypt($request->password),
            'classroom_id' => $request->classroom_id,
            'center_id' => $request->center_id,
            'device_id' => $request->device_id,
            'fcm_token' => $request->fcm_token,
            'activeted' => 1,
        ]);
        
        $token =  auth('api')->attempt(['phone' => $request->phone, 'password' => $request->password]);
        if(!$token){
            return Response::api(401,null,'غير مصرح لك');
        }

        $user = Student::with('center')->with(relations: 'classroom')->find(auth('api')->user()->id);
        return Response::api(200,['user'=>$user,'token'=>$token],'تم تسجيل الدخول بنجاح');
    }


    public function update(ApiEditProfile $request){

        $user = auth('api')->user();

        if ($request->hasFile('image')) {

            if(File::exists($user->image)){
                File::delete($user->image);
            }

            $image = ImageHelper::addImage($request->file('image'), 'students');
        }

        $user->update([
            'name' => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'parent_phone' => $request->parent_phone ?? $user->parent_phone,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'classroom_id' => $request->classroom_id ?? $user->classroom_id,
            'center_id' => $request->center_id ?? $user->center_id,
            'image' => $image ?? $user->image,
        ]);

        $user->load(['center','classroom']);
       
        return Response::api(200,$user,'تم التعديل بنجاح');
    }


    public function delete(Request $request){
        $user = auth('api')->user();
        Student::where('id',$user->id)->delete();
        return Response::api(200,null,'تم الحذف بنجاح');
    }


    
}
