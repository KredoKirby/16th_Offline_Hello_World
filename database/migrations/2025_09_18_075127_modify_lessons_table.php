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
    Schema::table('lessons', function (Blueprint $table) {
        // course_id があるなら削除
        if (Schema::hasColumn('lessons', 'course_id')) {
            $table->dropConstrainedForeignId('course_id');
        }

        // section_id を追加
        $table->foreignId('section_id')
              ->after('id')
              ->constrained()
              ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('lessons', function (Blueprint $table) {
        $table->dropConstrainedForeignId('section_id');
        $table->foreignId('course_id')->constrained()->cascadeOnDelete();
    });
}

};
