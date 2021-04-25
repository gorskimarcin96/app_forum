<?php


namespace App\Utils\Generate;


class ImageGenerateManager implements InterfaceImageGenerateManager
{
    public function makeRandImage(int $width = 800, int $height = 600, string $filePath = null): void
    {
        $img = imagecreatetruecolor($width, $height);
        $this->imageGrenadier($img, $width, $height, $this->randColor(), $this->randColor());

        if ($filePath) {
            imagepng($img, $filePath);
        } else {
            header('Content-Type: image/png');
            imagepng($img);
        }
    }

    private function randColor(): string
    {
        return sprintf('%06X', random_int(0, 0xFFFFFF));
    }

    private function imageGrenadier($img, int $x, int $y, string $start, string $end): void
    {
        if (0 > $x || 0 > $y) {
            return;
        }

        $s = [
            hexdec(substr($start, 0, 2)),
            hexdec(substr($start, 2, 2)),
            hexdec(substr($start, 4, 2))
        ];
        shuffle($s);
        $e = [
            hexdec(substr($end, 0, 2)),
            hexdec(substr($end, 2, 2)),
            hexdec(substr($end, 4, 2))
        ];
        shuffle($e);
        $steps = $y - 0;

        for ($i = 0; $i < $steps; $i++) {
            $r = $s[0] - ((($s[0] - $e[0]) / $steps) * $i);
            $g = $s[1] - ((($s[1] - $e[1]) / $steps) * $i);
            $b = $s[2] - ((($s[2] - $e[2]) / $steps) * $i);
            $color = imagecolorallocate($img, $r, $g, $b);
            imagefilledrectangle($img, 0, 0 + $i, $x, 0 + $i + 1, $color);
        }

    }
}