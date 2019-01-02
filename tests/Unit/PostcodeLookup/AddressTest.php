<?php declare(strict_types=1);

namespace Tests\Unit\PostcodeLookup;

use App\PostcodeLookup\Address;
use App\PostcodeLookup\GeoCoordinates;
use App\PostcodeLookup\AddressLines;
use App\PostcodeLookup\BuildingName;

class AddressTest extends \Tests\TestCase
{
    private function createNulledAddress(): Address
    {
        return new Address(
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
    }

    private function createExampleAddress(): Address
    {
        $stubbedCoords = $this->createMock(GeoCoordinates::class);
        $stubbedCoords
            ->method('getLatitude')
            ->willReturn(10.5);
        $stubbedCoords
            ->method('getLongitude')
            ->willReturn(5.25);

        $stubbedAddressLines = $this->createMock(AddressLines::class);

        $stubbedAddressLines
            ->method('getAddressLine1')
            ->willReturn('addressLine1');

        $stubbedAddressLines
        ->method('getAddressLine2')
        ->willReturn('addressLine2');

        $stubbedAddressLines
        ->method('getAddressLine3')
        ->willReturn('addressLine3');

        $stubbedBuilding = $this->createMock(BuildingName::class);
        $stubbedBuilding
            ->method('getBuildingName')
            ->willReturn('buildingName');

        $stubbedBuilding
            ->method('getBuildingNumber')
            ->willReturn('buildingNumber');

        return new Address(
            'premise',
            $stubbedBuilding,
            'thoroughfare',
            $stubbedAddressLines,
            'town',
            'postcode',
            'city',
            'county',
            'country',
            $stubbedCoords
        );
    }

    public function testEverythingReturnsWhatWasGiven(): void
    {
        $address = $this->createExampleAddress();
        $nullAddress = $this->createNulledAddress();

        $this->assertEquals('premise', $address->getFullPremise());
        $this->assertEquals('buildingNumber', $address->getBuildingNumber());
        $this->assertEquals('buildingName', $address->getBuildingName());
        $this->assertEquals('addressLine1', $address->getLine1());
        $this->assertEquals('addressLine2', $address->getLine2());
        $this->assertEquals('addressLine3', $address->getLine3());
        $this->assertEquals('town', $address->getTown());
        $this->assertEquals('postcode', $address->getPostcode());
        $this->assertEquals('city', $address->getCity());
        $this->assertEquals('county', $address->getCounty());
        $this->assertEquals('country', $address->getCountry());
        $this->assertEquals(5.25, $address->getLongitude());
        $this->assertEquals(10.5, $address->getLatitude());

        $this->assertNull($nullAddress->getFullPremise());
        $this->assertNull($nullAddress->getBuildingNumber());
        $this->assertNull($nullAddress->getBuildingName());
        $this->assertNull($nullAddress->getLine1());
        $this->assertNull($nullAddress->getLine2());
        $this->assertNull($nullAddress->getLine3());
        $this->assertNull($nullAddress->getTown());
        $this->assertNull($nullAddress->getPostcode());
        $this->assertNull($nullAddress->getCity());
        $this->assertNull($nullAddress->getCounty());
        $this->assertNull($nullAddress->getCountry());
        $this->assertNull($nullAddress->getLongitude());
        $this->assertNull($nullAddress->getLatitude());
    }
}
