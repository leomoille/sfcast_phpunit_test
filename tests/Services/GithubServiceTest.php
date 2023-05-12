<?php

namespace App\Tests\Services;

use App\Enum\healthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class GithubServiceTest extends TestCase
{
    private LoggerInterface $mockLogger;
    private MockHttpClient $mockHttpClient;
    private MockResponse $mockResponse;

    protected function setUp(): void
    {
        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->mockHttpClient = new MockHttpClient();
    }

    /**
     * @dataProvider dinoNameProvider
     */
    public function testGetHealthReportReturnsCorrectHealthStatusForDino(
        healthStatus $expectedStatus,
        string $dinoName
    ): void {
        $service = $this->createGithubService([
            [
                'title'  => 'Daisy',
                'labels' => [['name' => 'Status: Sick']],
            ],
            [
                'title'  => 'Maverick',
                'labels' => [['name' => 'Status: Healthy']],
            ],
        ]);

        self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
        self::assertSame(1, $this->mockHttpClient->getRequestsCount());
        self::assertSame('GET', $this->mockResponse->getRequestMethod());
        self::assertSame(
            'https://api.github.com/repos/SymfonyCasts/dino-park/issues',
            $this->mockResponse->getRequestUrl()
        );
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
        $service = $this->createGithubService([
            [
                'title'  => 'Maverick',
                'labels' => [['name' => 'Status: Drowsy']],
            ],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Drowsy is an unknown status label!');


        $service->getHealthReport('Maverick');
    }

    public function createGithubService(array $responseData): GithubService
    {
        $this->mockResponse = new MockResponse(json_encode($responseData));

        $this->mockHttpClient->setResponseFactory($this->mockResponse);

        return new GithubService($this->mockHttpClient, $this->mockLogger);
    }
}
