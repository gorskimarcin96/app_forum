<?php


namespace App\Utils;


use Exception;

trait EncodeJson
{
    public function encodeJson(string $data = null): array
    {
        $data = json_decode($data, true);
        $this->validateData($data);

        return $data ?? [];
    }

    private function validateData($data): void
    {
        if (!$data) {
            throw new Exception('Data is empty.');
        }
    }
}