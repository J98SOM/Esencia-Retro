<?php
$imgPath = 'public/img/logo.png';
$bwPath = 'public/img/logo-bw.png';
if (file_exists($imgPath)) {
    $src = imagecreatefrompng($imgPath);
    imagealphablending($src, false);
    imagesavealpha($src, true);
    
    $width = imagesx($src);
    $height = imagesy($src);
    
    // Create new image
    $dst = imagecreatetruecolor($width, $height);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
    imagefill($dst, 0, 0, $transparent);
    
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgba = imagecolorat($src, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;
            
            // If it's very white or fully transparent, leave it transparent/white
            if ($alpha > 120 || ($r > 240 && $g > 240 && $b > 240)) {
                imagesetpixel($dst, $x, $y, $transparent);
            } else {
                // Otherwise make it black
                $black = imagecolorallocatealpha($dst, 0, 0, 0, $alpha);
                imagesetpixel($dst, $x, $y, $black);
            }
        }
    }
    imagepng($dst, $bwPath);
    imagedestroy($src);
    imagedestroy($dst);
    echo "Logo converted to solid black.";
}
