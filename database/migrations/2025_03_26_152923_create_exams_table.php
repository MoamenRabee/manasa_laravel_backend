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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('minutes')->nullable();
            $table->enum('status', ['pending','published','published_show_results','show_results'])->default('pending');
            $table->foreignId('lesson_id')->constrained('lessons','id')->onDelete('cascade')->nullable();
            $table->foreignId('classroom_id')->constrained('classrooms','id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
