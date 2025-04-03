<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('require_exam_id')->nullable()->after('classroom_id');
            $table->foreign('require_exam_id')
                  ->references('id')
                  ->on('exams')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['require_exam_id']);
            $table->dropColumn('require_exam_id');
        });
    }
};
