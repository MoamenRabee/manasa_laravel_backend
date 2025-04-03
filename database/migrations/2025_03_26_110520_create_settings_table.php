<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('android_build_number')->default(0);
            $table->integer('ios_build_number')->default(0);
            $table->string('android_link')->nullable();
            $table->string('ios_link')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->string('closed_message')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook')->nullable();
            $table->string('telegram')->nullable();
            $table->string('website')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
