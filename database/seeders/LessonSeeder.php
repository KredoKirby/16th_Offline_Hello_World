<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Course;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        Lesson::truncate();

        $laravel = Course::where('title', 'Laravel入門')->first();
        $bootstrap = Course::where('title', 'Bootstrapデザイン')->first();

        if ($laravel) {
            Lesson::create([
                'course_id' => $laravel->id,
                'title' => '環境構築',
                'content' => 'Laravelプロジェクトをセットアップします。',
            ]);
            Lesson::create([
                'course_id' => $laravel->id,
                'title' => 'ルーティング',
                'content' => 'Routeの基本を学びます。',
            ]);
        }

        if ($bootstrap) {
            Lesson::create([
                'course_id' => $bootstrap->id,
                'title' => 'レイアウトの基本',
                'content' => 'BootstrapのGridを学びます。',
            ]);
            Lesson::create([
                'course_id' => $bootstrap->id,
                'title' => 'コンポーネントの利用',
                'content' => 'ButtonやCardを使ってみましょう。',
            ]);
        }
    }
}
