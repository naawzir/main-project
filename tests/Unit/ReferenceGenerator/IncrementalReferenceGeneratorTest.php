<?php declare(strict_types=1);

namespace Tests\Unit\ReferenceGenerator;

use App\ReferenceGenerator\IncrementalReferenceGenerator;
use App\ReferenceGenerator\IncrementalReferenceStorage;
use App\ReferenceGenerator\Reference;
use App\ReferenceGenerator\ReferenceNotGeneratedException;
use PHPUnit\Framework\MockObject\MockObject;

class IncrementalReferenceGeneratorTest extends \Tests\TestCase
{
    public function testGenerateNextReferenceStoresIncrementedReference(): void
    {
        /** @var MockObject|Reference $reference */
        $reference = $this->createMock(Reference::class);

        $reference
            ->method('toString')
            ->willReturn('10');

        /** @var MockObject|IncrementalReferenceStorage $storage */
        $storage = $this->createMock(IncrementalReferenceStorage::class);

        $storage
            ->expects($this->once())
            ->method('loadLastReference')
            ->will($this->returnValue($reference));

        // Assert that the value it wants to store has been incremented.
        $storage
            ->expects($this->once())
            ->method('storeNewReference')
            ->with($this->callback(function (Reference $reference) {
                return $reference->toString() === '11';
            }));

        $generator = new IncrementalReferenceGenerator($storage);
        $generator->generateNextReference();

        $result = $generator->getGeneratedReference();

        $this->assertEquals('11', $result->toString());
    }

    public function testRetrievingBeforeGeneratingThrowsException(): void
    {
        $this->expectException(ReferenceNotGeneratedException::class);

        /** @var MockObject|IncrementalReferenceStorage $storage */
        $storage = $this->createMock(IncrementalReferenceStorage::class);

        $generator = new IncrementalReferenceGenerator($storage);

        $generator->getGeneratedReference();
    }
}
