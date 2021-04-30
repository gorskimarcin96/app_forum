<?php

namespace App\Tests\Utils;

use App\Utils\EncodeJson;
use Exception;
use PHPUnit\Framework\TestCase;

class EncodeJsonTest extends TestCase
{
    use EncodeJson;

    public function testEncodeJson(): void
    {
        $arrayFromEncode = $this->encodeJsonToArray('{"name":"Marian"}');

        self::assertEquals(['name' => 'Marian'], $arrayFromEncode);
    }

    public function testEncodeJsonBig(): void
    {
        $stringJson = '{"name":"Stefan","email":"małpa@małpa.małpa","address":{"city":"Krasnystaw","city_code":"22-300"}}';
        $arrayFromEncode = $this->encodeJsonToArray($stringJson);

        self::assertEquals(
            [
                'name' => 'Stefan',
                'email' => 'małpa@małpa.małpa',
                'address' => ['city' => 'Krasnystaw', 'city_code' => '22-300']
            ],
            $arrayFromEncode
        );
    }

    public function testEncodeJsonNoValidWrongString(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('JSON is not valid.');

        $this->encodeJsonToArray('{"name":"Maria');
    }

    public function testEncodeJsonNoValidEmptyString(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('JSON is not valid.');

        $this->encodeJsonToArray('');
    }

    public function testEncodeJsonNoValidEmptyJson(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('JSON is empty.');

        $this->encodeJsonToArray('{}');
    }
}
