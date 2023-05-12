<?php

namespace App\Tests\Services;

use App\Enum\healthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;

class GithubServiceTest extends TestCase
{
    /**
     * @dataProvider dinoNameProvider
     */
    public function testGetHealthReportReturnsCorrectHealthStatusForDino(
        healthStatus $expectedStatus,
        string $dinoName
    ): void {
        $service = new GithubService();

        self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
    }

    public function dinoNameProvider(): \Generator
    {
        yield 'Sick Dino' => [
            healthStatus::SICK,
            'Daisy',
        ];
        yield 'Healthy Dino' => [
            healthStatus::HEALTHY,
            'Maverick',
        ];
    }
}
