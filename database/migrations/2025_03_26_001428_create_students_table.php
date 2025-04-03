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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('parent_phone');
            $table->string('password');
            $table->foreignId('classroom_id')->constrained('classrooms','id')->onDelete('cascade');
            $table->foreignId('center_id')->constrained('centers','id')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('device_id')->nullable();
            $table->boolean('activeted')->default(true);
            $table->string('fcm_token')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
