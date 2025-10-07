<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->json('images')->nullable()->after('content');
            $table->json('thumbs')->nullable()->after('images');
            $table->integer('pages')->default(0)->after('thumbs');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['images', 'thumbs', 'pages']);
        });
    }
};
