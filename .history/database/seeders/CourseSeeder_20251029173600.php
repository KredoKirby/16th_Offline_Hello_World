<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------------
        // デフォルト画像（Base64）
        // -------------------------------------------------------------
        $defaultImagePath = public_path('images/default-course.jpg');
        $defaultBase64 = file_exists($defaultImagePath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($defaultImagePath))
            : null;

    }
    private function encodeImages(array $filenames): array
    {
        $base64Array = [];

        foreach ($filenames as $filename) {
            $path = public_path('lessons/thumbs/' . $filename);


            if (file_exists($path)) {
                $mime = mime_content_type($path);
                $base64Array[] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            } else {
                $base64Array[] = null; 
            }
        }

        return $base64Array;
    }
    
}
