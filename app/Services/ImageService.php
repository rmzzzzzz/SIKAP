<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    public static function compressAndStore($file, $folder = 'laporan')
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file);

        //Resize maksimal lebar
        $image->scaleDown(width: 1200);

        //Encode ke JPG kualitas 75%
        $encoded = $image->toJpeg(75);

        $filename = $folder . '/' . uniqid() . '.jpg';

        Storage::disk('public')->put($filename, $encoded);

        return $filename;
    }
}
