<?php


namespace App\Utils\Generate;


interface InterfaceImageGenerateManager
{
    public function makeRandImage(int $width = 800, int $height = 600, string $filePath = null): void;
}