<?php


namespace App\Utils;


use Exception;

class ImageGenerateManager
{
    /**
     * @param int $width
     * @param int $height
     * @param false $filePath
     * @throws Exception
     */
    public function makeRandImage($width = 800, $height = 600, $filePath = false): void
    {
        $img = imagecreatetruecolor($width, $height);
        $this->imageGrenadier($img, 0, 0, $width, $height, $this->randColor(), $this->randColor());

        if ($filePath) {
            imagepng($img, $filePath);
        } else {
            header('Content-Type: image/png');
            imagepng($img);
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    private function randColor(): string
    {
        return sprintf('%06X', random_int(0, 0xFFFFFF));
    }

    /**
     * @param $img
     * @param $x
     * @param $y
     * @param $x1
     * @param $y1
     * @param $start
     * @param $end
     * @return bool
     */
    private function imageGrenadier($img, $x, $y, $x1, $y1, $start, $end): bool
    {
        if ($x > $x1 || $y > $y1) {
            return false;
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
        $steps = $y1 - $y;

        for ($i = 0; $i < $steps; $i++) {
            $r = $s[0] - ((($s[0] - $e[0]) / $steps) * $i);
            $g = $s[1] - ((($s[1] - $e[1]) / $steps) * $i);
            $b = $s[2] - ((($s[2] - $e[2]) / $steps) * $i);
            $color = imagecolorallocate($img, $r, $g, $b);
            imagefilledrectangle($img, $x, $y + $i, $x1, $y + $i + 1, $color);
        }

        return true;
    }
}