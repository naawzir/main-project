<?php declare(strict_types=1);

namespace Tests\Unit\PostcodeLookup;

use App\PostcodeLookup\Address;
use App\PostcodeLookup\AddressIterator;
use PHPUnit\Framework\MockObject\MockObject;

class AddressIteratorTest extends \Tests\TestCase
{
    private function createMockAddress(): Address
    {
        return $this->createMock(Address::class);
    }

    private function createMockAddressArray(): array
    {
        return [
            $this->createMockAddress(),
            $this->createMockAddress(),
            $this->createMockAddress(),
            $this->createMockAddress(),
            $this->createMockAddress(),
        ];
    }

    public function testGetCurrentShouldReturnCurrentAddress(): void
    {
        $addresses = $this->createMockAddressArray();

        $iterator = new AddressIterator(...$addresses);

        $this->assertSame($addresses[0], $iterator->current());
    }

    public function testGetNextShouldAdvanceTheCurrentKey(): void
    {
        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $iterator->next();
        $this->assertSame($addresses[1], $iterator->current());

        $iterator->next();
        $this->assertSame($addresses[2], $iterator->current());

        $iterator->next();
        $this->assertSame($addresses[3], $iterator->current());
    }

    public function testGetKeyShouldReturnCurrentKey(): void
    {
        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $this->assertEquals(0, $iterator->key());
        $iterator->next();
        $this->assertEquals(1, $iterator->key());
        $iterator->next();
        $this->assertEquals(2, $iterator->key());
        $iterator->next();
        $this->assertEquals(3, $iterator->key());
    }

    public function testValidReturnsTrueOnValidKeysAndFalseOnInvalidKeys(): void
    {
        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());

        $iterator->next();
        $this->assertFalse($iterator->valid());
        $iterator->next();
        $this->assertFalse($iterator->valid());
    }

    public function testRewindResetsCurrentPosition(): void
    {
        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $iterator->next();
        $iterator->next();
        $iterator->next();
        $this->assertEquals(3, $iterator->key());
        $iterator->rewind();
        $this->assertEquals(0, $iterator->key());
    }

    public function testSeekChangesPosition(): void
    {
        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $iterator->seek(4);
        $this->assertEquals(4, $iterator->key());
    }

    public function testSeekingTooHighThrowsOutOfBoundsException(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $iterator->seek(10);
    }

    public function testCountingReturnsCorrectTotal(): void
    {
        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $this->assertEquals(5, $iterator->count());
    }

    public function testReturningArrayReturnsOriginalArray(): void
    {
        $addresses = $this->createMockAddressArray();
        $iterator = new AddressIterator(...$addresses);

        $this->assertEquals($addresses, $iterator->toArray());
    }

    public function testCanBeJsonEncoded(): void
    {
        $addresses = $this->createMockAddressArray();

        $encodedAddresses = json_encode($addresses);

        $iterator = new AddressIterator(...$addresses);

        $this->assertEquals($encodedAddresses, json_encode($iterator));
    }
}
