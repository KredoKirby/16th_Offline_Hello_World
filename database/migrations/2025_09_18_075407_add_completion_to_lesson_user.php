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
    Schema::table('lesson_user', function (Blueprint $table) {
        if (!Schema::hasColumn('lesson_user', 'is_completed')) {
            $table->boolean('is_completed')->default(false);
        }
        if (!Schema::hasColumn('lesson_user', 'completed_at')) {
            $table->timestamp('completed_at')->nullable();
        }
    });
}


public function down(): void
{
    Schema::table('lesson_user', function (Blueprint $table) {
        $table->dropColumn(['is_completed', 'completed_at']);
    });
}

};
