<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use App\Enum\healthStatus;
use Generator;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    public function testCanGetAndSetData(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock A'
        );

        self::assertSame('Big Eaty', $dino->getName());
        self::assertSame('Tyrannosaurus', $dino->getGenus());
        self::assertSame(15, $dino->getLength());
        self::assertSame('Paddock A', $dino->getEnclosure());
    }

    /**
     * @dataProvider sizeDescriptionProvider
     */
    public function testDinosaurHasCorrectSizeDescriptionFromLength(int $length, string $exceptedSize): void
    {
        $dino = new Dinosaur(name: 'Big Eaty', length: $length);

        self::assertSame($exceptedSize, $dino->getSizeDescription());
    }

    public function sizeDescriptionProvider(): Generator
    {
        yield '10 Meter Large Dino' => [10, 'Large'];
        yield '5 Meter Medium Dino' => [5, 'Medium'];
        yield '4 Meter Small Dino' => [4, 'Small'];
    }

    public function testIsAcceptingVisitorsByDefault(): void
    {
        $dino = new Dinosaur('Dennis');

        self::assertTrue($dino->isAcceptingVisitors());
    }

    /**
     * @dataProvider healthStatusProvider
     */
    public function testIsAcceptingVisitorsBasedOnHealthStatus(
        healthStatus $healthStatus,
        bool $expectedVisitorsStatus
    ): void {
        $dino = new Dinosaur('Bumpy');
        $dino->setHealth($healthStatus);

        self::assertSame($expectedVisitorsStatus, $dino->isAcceptingVisitors());
    }

    public function healthStatusProvider(): Generator
    {
        yield 'Sick dino is not accepting visitors' => [healthStatus::SICK, false];
        yield 'Hungry dino is accepting visitors' => [healthStatus::HUNGRY, true];
    }

}
