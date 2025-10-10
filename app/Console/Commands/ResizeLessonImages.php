<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use File;

class ResizeLessonImages extends Command
{
    protected $signature = 'lessons:resize-images';
    protected $description = 'Resize lesson images for central view and thumbnails';

    public function handle()
    {
        $imageDir = public_path('images/lessons');
        $files = File::files($imageDir);

        foreach ($files as $file) {
            $filename = $file->getFilename();

            $this->info("Processing $filename...");

            $img = Image::make($file->getRealPath());

            // 中央表示用: 最大幅600px
            $img->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($imageDir . '/' . $filename); // 上書き

            // サムネイル用: 幅50px
            $thumb = Image::make($file->getRealPath());
            $thumb->resize(50, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumb->save($imageDir . '/thumb_' . $filename);
        }

        $this->info("All images resized successfully!");
    }
}
