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
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->integer('sort_number');
            $table->enum('type', ['month','package'])->default('month');
            $table->boolean('is_free')->default(value: 1);
            $table->decimal('price')->nullable();
            $table->boolean('is_active')->default(1);
            $table->foreignId('classroom_id')->constrained('classrooms','id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systems');
    }
};
