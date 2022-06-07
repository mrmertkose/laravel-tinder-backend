<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

trait ImageUpload
{
    public function upload($file, $path, $width = null, $height = null): ?string
    {
        $name = null;
        if ($file) {
            $path = public_path($path);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $name = uniqid() . '.' . $file->getClientOriginalExtension();
            $public_path = $path . $name;
            $img = Image::make($file->getRealPath());
            !is_null($width) ?? $img->resize($width, $height);
            $img->save($public_path);
        }
        return $name;
    }
}
