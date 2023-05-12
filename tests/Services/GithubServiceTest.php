<?php

namespace App\Tests\Services;

use App\Enum\healthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubServiceTest extends TestCase
{
    /**
     * @dataProvider dinoNameProvider
     */
    public function testGetHealthReportReturnsCorrectHealthStatusForDino(
        healthStatus $expectedStatus,
        string $dinoName
    ): void {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockClient = $this->createMock(HttpClientInterface::class);

        $service = new GithubService($mockClient, $mockLogger);

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
