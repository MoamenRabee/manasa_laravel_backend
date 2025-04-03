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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->foreignId('lesson_id')->constrained('lessons','id')->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained('classrooms','id')->onDelete('cascade');
            $table->integer('sort_number');
            $table->integer('views_count');
            $table->integer('duration');
            $table->boolean('is_active')->default(1);
            $table->text('description')->nullable();
            $table->enum('link_type', ['youtube','vimeo'])->default('youtube');
            $table->boolean('is_free')->default(value: 1);
            $table->decimal('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
