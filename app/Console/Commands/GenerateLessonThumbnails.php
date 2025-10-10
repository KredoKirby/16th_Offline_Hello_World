<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lesson;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class GenerateLessonThumbnails extends Command
{
    protected $signature = 'lessons:generate-thumbs';
    protected $description = 'Generate thumbnails for all lesson images';

    public function handle()
    {
        $lessons = Lesson::all();
        $this->info('Generating thumbnails for '. $lessons->count() .' lessons...');

        foreach($lessons as $lesson){
            if(!$lesson->images) continue;

            foreach($lesson->images as $index => $imgFile){
                $original = public_path('images/lessons/'.$imgFile);
                if(!File::exists($original)) continue;

                $thumbDir = public_path('images/lessons/thumbs');
                if(!File::exists($thumbDir)) File::makeDirectory($thumbDir, 0755, true);

                $thumbPath = $thumbDir.'/'.$lesson->id.'_'.$index.'_thumb.png';

                if(!File::exists($thumbPath)){
                    $img = Image::make($original)->fit(50,35);
                    $img->save($thumbPath);
                    $this->info("Created thumbnail: {$thumbPath}");
                }
            }
        }

        $this->info('All thumbnails generated.');
        return 0;
    }
}
