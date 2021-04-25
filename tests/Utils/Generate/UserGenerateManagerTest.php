<?php

namespace App\Tests\Utils\Generate;

class UserGenerateManagerTest extends GenerateKernelTestCase
{
    public function testExecuteOkQuantity2(): void
    {
        $countDataBefore = $this->userRepository->count();
        $this->userGenerateManager->generate(2);
        $countDataAfter = $this->userRepository->count();

        self::assertSame($countDataBefore + 2, $countDataAfter);
    }

    public function testExecuteOkQuantity0(): void
    {
        $countDataBefore = $this->userRepository->count();
        $this->userGenerateManager->generate(0);
        $countDataAfter = $this->userRepository->count();

        self::assertSame($countDataBefore + 0, $countDataAfter);
    }

    public function testExecuteOkQuantityMinus2(): void
    {
        $countDataBefore = $this->userRepository->count();
        $this->userGenerateManager->generate(-2);
        $countDataAfter = $this->userRepository->count();

        self::assertSame($countDataBefore + 0, $countDataAfter);
    }
}
