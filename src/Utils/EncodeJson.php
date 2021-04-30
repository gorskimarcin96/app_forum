<?php


namespace App\Utils;


use Exception;

trait EncodeJson
{
    public function encodeJsonToArray(string $data = null): array
    {
        $data = json_decode($data, true);
        $this->isValidate();
        $this->validateData($data);

        return $data ?? [];
    }

    private function isValidate(): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON is not valid.');
        }
    }

    private function validateData($data): void
    {
        if (!$data) {
            throw new Exception('JSON is empty.');
        }
    }
}