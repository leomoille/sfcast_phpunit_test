<?php

namespace App\Tests\Services;

use App\Enum\healthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
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
            ->expects(self::once())
            ->method('request')
            ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
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

    public function testExceptionThrowWithUnknownLabel(): void
    {
        $mockResponse = new MockResponse(
            json_encode([
                [
                    'title'  => 'Maverick',
                    'labels' => [['name' => 'Status: Drowsy']],
                ],
            ])
        );


        $mockHttpClient = new MockHttpClient($mockResponse);

        $service = new GithubService($mockHttpClient, $this->createMock(LoggerInterface::class));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Drowsy is an unknown status label!');


        $service->getHealthReport('Maverick');
    }
}
