<?php

namespace App\Tests\Services;

use App\Enum\healthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $mockResponse
            ->method('toArray')
            ->willReturn([
                [
                    'title'  => 'Daisy',
                    'labels' => [['name' => 'Status: Sick']],
                ],
                [
                    'title'  => 'Maverick',
                    'labels' => [['name' => 'Status: Healthy']],
                ],
            ]);

        $mockHttpClient
            ->method('request')
            ->willReturn($mockResponse);

        $service = new GithubService($mockHttpClient, $mockLogger);

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
