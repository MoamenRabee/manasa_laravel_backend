<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro("api", function ($status, $data = null, $message = null) {
            return Response::json([
                "status" => $status === 200 ? true : false,
                "statusCode" => $status,
                "data" => $data,
                "message" => $message,
            ],status: $status);
        });
    }
}
