<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // 予約（bookings）への外部キー。1 booking に 1 report 想定なら unique を付与
            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->cascadeOnDelete()
                  ->unique();

            // ステータス：attended / absent / leave in the middle
            // ※ MySQL の ENUM はスペース入りでもOK。アプリ側は文字列を正確に一致させて保存してください。
            $table->enum('status', ['attended', 'absent', 'leave in the middle'])
                  ->default('attended');

            // フィードバック：任意なので NULL 可
            $table->text('feedback')->nullable();

            // 次に進むべきトピックID（topics.id）— 任意なので NULL 可
            $table->foreignId('next_topic')
                  ->nullable()
                  ->constrained('topics')
                  ->nullOnDelete();

            // タイムスタンプ（作成・更新）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};