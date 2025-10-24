<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_course', function (Blueprint $table) {
            // 推奨: ピボットはid不要。必要なら $table->id(); を追加してOK。
            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            // 同じ teacher と course の重複を禁止
            $table->unique(['teacher_id', 'course_id']);

            // 検索用の補助インデックス（任意）
            $table->index('teacher_id');
            $table->index('course_id');

            // 付けるならタイムスタンプ（任意）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_course');
    }
};
