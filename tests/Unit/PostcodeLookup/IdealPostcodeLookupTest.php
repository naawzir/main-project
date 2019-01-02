<?php declare(strict_types=1);

namespace Tests\Unit\PostcodeLookup;

use Academe\IdealPostcodes\Address as IdealAddress;
use Academe\IdealPostcodes\Postcode;
use App\PostcodeLookup\IdealPostcodeLookup;
use App\PostcodeLookup\PostcodeLookupException;
use PhpCollection\Sequence;
use Tests\TestCase;

class IdealPostcodeLookupTest extends TestCase
{
    private function createMockAddress(): IdealAddress
    {
        $mock = $this->createMock(IdealAddress::class);

        $mock->fullpremise = 'fullpremise';
        $mock->building_number = 'building_number';
        $mock->building_name = 'building_name';
        $mock->line_1 = 'line_1';
        $mock->line_2 = 'line_2';
        $mock->line_3 = 'line_3';
        $mock->town = 'town';
        $mock->postcode = 'postcode';
        $mock->district = 'district';
        $mock->county = 'county';
        $mock->country = 'country';
        $mock->longitude = 20.20;
        $mock->latitude = 10.5;

        return $mock;
    }

    private function createMockCollection(): Sequence
    {
        $sequenceMock = new Sequence();
        $sequenceMock->add($this->createMockAddress());
        $sequenceMock->add($this->createMockAddress());
        $sequenceMock->add($this->createMockAddress());
        $sequenceMock->add($this->createMockAddress());
        $sequenceMock->add($this->createMockAddress());
        return $sequenceMock;
    }

    private function createPostcodePackageMock($responseCode = 2000, $responseMessage = 'Example Message'): Postcode
    {
        $mock = $this->createMock(Postcode::class);

        $mock->method('getCode')
            ->will($this->returnValue($responseCode));

        $mock->method('getMessage')
            ->will($this->returnValue($responseMessage));

        $mock->method('getAddresses')
            ->will($this->returnValue($this->createMockCollection()));

        return $mock;
    }

    public function testNormalUseOfLookupReturnsAddresses(): void
    {
        $mock = $this->createPostcodePackageMock();

        $instance = new IdealPostcodeLookup($mock);

        $this->assertEquals(5, $instance->lookupByPostcode('NE1 1AB')->count());
    }

    public function testBadResponseFromLookupThrowsException(): void
    {
        $this->expectException(PostcodeLookupException::class);
        $this->expectExceptionMessageRegExp('#.*\bBad Problem\b.*#');

        $mock = $this->createPostcodePackageMock(1000, 'Bad Problem');

        $instance = new IdealPostcodeLookup($mock);

        $instance->lookupByPostcode('NE1 1AB');
    }
}
