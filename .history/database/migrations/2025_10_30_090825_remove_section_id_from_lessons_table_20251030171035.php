<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['section_id']);
            // カラムを削除
            $table->dropColumn('section_id');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // 元に戻す処理（nullableでOK）
            $table->unsignedBigInteger('section_id')->nullable()->after('id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }
};
