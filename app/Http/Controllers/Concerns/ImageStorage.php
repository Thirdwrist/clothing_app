<?php
namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


trait ImageStorage{
    private function uploadImage($image, $connection = null )
    {
        $connection = $connection ?? config('filesystems.default');
        $extension = $image->getClientOriginalExtension();
        $name = Str::slug(now()->toDayDateTimeString())
            .random_int(20, 3000000)
            .'.'
            .$extension;

        Storage::disk($connection)->put($path = "public/images/thread_posts/$name", fopen($image, 'r+'));

        return $path;
    }
}