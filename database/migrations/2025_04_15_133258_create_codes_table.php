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
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codes_group_id')->constrained('codes_groups', 'id')->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained('lessons', 'id')->onDelete('cascade');
            $table->foreignId('system_id')->nullable()->constrained('systems', 'id')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('students', 'id')->onDelete('cascade');
            $table->string('code')->unique();
            $table->float('price')->nullable();
            $table->boolean('is_used')->default(false);
            $table->dateTime('used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};
