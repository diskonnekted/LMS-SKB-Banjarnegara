<?php

namespace App\Http\Controllers;

class IconController extends Controller
{
    public function icon(int $size)
    {
        $size = in_array($size, [192, 512]) ? $size : 192;
        $path = public_path('logo.png');
        if (! file_exists($path)) {
            $fallback = public_path('images/black.png');
            $path = file_exists($fallback) ? $fallback : null;
        }
        if ($path) {
            $source = @imagecreatefrompng($path);
            if (! $source) {
                $source = @imagecreatefromjpeg($path);
            }
        } else {
            $source = null;
        }
        if (! $source) {
            $img = imagecreatetruecolor($size, $size);
            $bg = imagecolorallocate($img, 108, 92, 231);
            imagefilledrectangle($img, 0, 0, $size, $size, $bg);
            ob_start();
            imagepng($img);
            $data = ob_get_clean();
            imagedestroy($img);

            return response($data, 200)->header('Content-Type', 'image/png');
        }
        $width = imagesx($source);
        $height = imagesy($source);
        $canvas = imagecreatetruecolor($size, $size);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $size, $size, $transparent);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $size, $size, $width, $height);
        ob_start();
        imagepng($canvas);
        $data = ob_get_clean();
        imagedestroy($source);
        imagedestroy($canvas);

        return response($data, 200)->header('Content-Type', 'image/png');
    }
}
