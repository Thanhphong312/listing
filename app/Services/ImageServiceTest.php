<?php

namespace Vanguard\Services;

use GdImage;
use Vanguard\Models\Images;
use Treinetic\ImageArtist\lib\Text\Color;
use Treinetic\ImageArtist\lib\Text\Font;
use Treinetic\ImageArtist\lib\Text\TextBox;

class ImageServiceTest extends ModelService
{
    public function __construct()
    {
        $this->model = resolve(Images::class);
    }

    public function findByItemId (int $itemId) {
        return $this->model->where('order_item_id')->first();
    }

    public function makeWhiteBackground(int $fileWidth, int $fileHeight, $folder = "blob") {
        $folderPath = storage_path("app/image/{$folder}");

        // Create the folder if it doesn't exist
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $imageCreate = @imagecreate($fileWidth, $fileHeight) or die("Cannot Initialize new GD image stream");
        $white       = imagecolorallocate($imageCreate, 255, 255, 255);

        imagefilltoborder($imageCreate, 0, 0, $white, $white);
        imagefill($imageCreate, 50, 20, $white);

        for ($i = 1; $i < 8; $i++) {
            $cord = $i * $fileWidth;
            imageline($imageCreate, 0, $cord, 400, $cord, $white);
            imageline($imageCreate, $cord, 0, $cord, 400, $white);
        }

        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                $x = ($i * $fileWidth) + 2;
                $y = ($j * $fileWidth) + 2;
                if ((($i % 2) + ($j % 2)) % 2 == 0) {
                    imagefill($imageCreate, $x, $y, $white);
                }
            }
        }

        $backgroundPath = storage_path("app/image/{$folder}/background.png");
        imagepng($imageCreate, $backgroundPath);
        imagedestroy($imageCreate);
        return $backgroundPath;
    }

    public function makeWhiteBackgroundQr(int $fileWidth, int $fileHeight, $folder = "blob", $nameqr) {
        $folderPath = storage_path("app/image/{$folder}");

        // Create the folder if it doesn't exist
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $imageCreate = @imagecreate($fileWidth, $fileHeight) or die("Cannot Initialize new GD image stream");
        $transparent = imagecolorallocatealpha($imageCreate, 0, 0, 0, 127); // Không màu là màu rgba(0, 0, 0, 127)
        imagecolortransparent($imageCreate, $transparent); // Đặt màu trong suốt

        // Làm cho tất cả các điểm ảnh trong hình ảnh trở thành màu trong suốt
        imagefill($imageCreate, 0, 0, $transparent);

        // Vẽ các đường line
        for ($i = 1; $i < 8; $i++) {
            $cord = $i * $fileWidth;
            imageline($imageCreate, 0, $cord, 400, $cord, $transparent);
            imageline($imageCreate, $cord, 0, $cord, 400, $transparent);
        }
        
        // Tạo border nét đứt
        $borderColor = imagecolorallocate($imageCreate, 0, 0, 0); // Đặt màu border là màu đen
        $borderWidth = 2; // Độ rộng của border
        $borderGap = 5; // Khoảng cách giữa các đoạn nét đứt

        // // Top border
        // imagedashedline($imageCreate, 0, 0, $fileWidth - 1, 0, $borderColor);
        // // Left border
        // imagedashedline($imageCreate, 0, 0, 0, $fileHeight - 1, $borderColor);
        // // Bottom border
        // imagedashedline($imageCreate, 0, $fileHeight - 1, $fileWidth - 1, $fileHeight - 1, $borderColor);
        // // Right border
        // imagedashedline($imageCreate, $fileWidth - 1, 0, $fileWidth - 1, $fileHeight - 1, $borderColor);


        // Tạo các ô vuông đen trắng
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                $x = ($i * $fileWidth) + 2;
                $y = ($j * $fileWidth) + 2;
                if ((($i % 2) + ($j % 2)) % 2 == 0) {
                    imagefill($imageCreate, $x, $y, $transparent);
                }
            }
        }

        $backgroundPath = storage_path("app/image/{$folder}/background.png");
        imagepng($imageCreate, $backgroundPath);
        imagedestroy($imageCreate);
        return $backgroundPath;
    }


    public function mergeImage ( int $dst_x,int $dst_y, int $width, int $height, GdImage $img1, GdImage $img2, string $path) {
        imagecopy($img1, $img2, $dst_x, $dst_y, 0, 0, $width, $height);
        imagepng($img1, $path);
    }

    public function writeText( GdImage $img1, int $x, int $y, string $text, int $size = 10, string $color = 'black', int $bold = 0) {
        if($color == 'black') {
            $tColor = imagecolorallocate($img1, 0, 0, 0);
        }else {
            $tColor = imagecolorallocate($img1, 255, 0, 0);
        }
        $font = public_path('fonts/verdana.ttf');
        if($bold == 1) {
            $font = public_path('fonts/verdanabold.ttf');
        }
        imagettftext($img1, $size, 0, $x, $y, $tColor, $font, $text);
    }

    public function resizeImage(GdImage $image, $w, $h) {
        $oldw = imagesx($image);
        $oldh = imagesy($image);
        $temp = imagecreatetruecolor($w, $h);
        imagecopyresampled($temp, $image, 0, 0, 0, 0, $w, $h, $oldw, $oldh);
        return $temp;
    }
    public function resizeImageWithoutBackground(GdImage $image, $w, $h) {
        $oldw = imagesx($image);
        $oldh = imagesy($image);
        $temp = imagecreatetruecolor($w, $h);
        imagecopyresampled($temp, $image, 0, 0, 0, 0, $w, $h, $oldw, $oldh);
        return $temp;
    }
    
    
    public function png2jpg(GdImage $pngImage, string $outputFile, int $width, int $height, int $quality = 100) {
        $output = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($output,  255, 255, 255);
        imagefilledrectangle($output, 0, 0, $width, $height, $white);
        imagecopy($output, $pngImage, 0, 0, 0, 0, $width, $height);
        imagejpeg($output, $outputFile, $quality);
        return $outputFile;
    }

    private function createTransparentTemplate($width, $height){
        $copy = imagecreatetruecolor($width, $height);
        $color = imagecolorallocatealpha($copy, 0, 0, 0, 127);
        imagefill($copy, 0, 0, $color);
        imagesavealpha($copy, true);
        return $copy;
    }

    public function makeGdImage(string $path) {
        $info = getimagesize($path);
        $type = $info[2];
        return match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_GIF => imagecreatefromgif($path),
            default => @imagecreatefrompng($path),
        };
    }
}
