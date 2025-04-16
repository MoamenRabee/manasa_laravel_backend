<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feed;
use Illuminate\Support\Facades\Response;

class FeedsController extends Controller
{
    public function index()
    {
        $feeds = Feed::orderBy("created_at","desc")->get();
        return Response::api(200, $feeds,'تم جلب البيانات بنجاح');
    }
}
