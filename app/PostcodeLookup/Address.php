<?php declare(strict_types=1);

namespace App\PostcodeLookup;

use JsonSerializable;

class Address implements JsonSerializable
{
    /**
     * @var BuildingName
     */
    private $buildingName;
    /**
     * @var null|string
     */
    private $thoroughfare;
    /**
     * @var AddressLines
     */
    private $addressLines;
    /**
     * @var string
     */
    private $town;
    /**
     * @var string
     */
    private $postcode;
    /**
     * @var string
     */
    private $county;
    /**
     * @var string
     */
    private $city;
    /**
     * @var GeoCoordinates
     */
    private $coordinates;
    /**
     * @var string
     */
    private $fullPremise;
    /**
     * @var string
     */
    private $country;

    public function __construct(
        ?string $fullPremise,
        ?BuildingName $buildingName,
        ?string $thoroughFare,
        ?AddressLines $addressLines,
        ?string $town,
        ?string $postcode,
        ?string $city,
        ?string $county,
        ?string $country,
        ?GeoCoordinates $location
    ) {
        $this->fullPremise = $fullPremise;
        $this->buildingName = $buildingName;
        $this->thoroughfare = $thoroughFare;
        $this->addressLines = $addressLines;
        $this->town = $town;
        $this->postcode = $postcode;
        $this->county = $county;
        $this->city = $city;
        $this->country = $country;
        $this->coordinates = $location;
    }

    /**
     * @return string
     */
    public function getBuildingNumber(): ?string
    {
        return $this->buildingName ? $this->buildingName->getBuildingNumber() : null;
    }

    /**
     * @return string
     */
    public function getBuildingName(): ?string
    {
        return $this->buildingName ? $this->buildingName->getBuildingName() : null;
    }

    /**
     * @return null|string
     */
    public function getThoroughfare(): ?string
    {
        return $this->thoroughfare;
    }

    /**
     * @return string
     */
    public function getLine1(): ?string
    {
        return $this->addressLines ? $this->addressLines->getAddressLine1() : null;
    }

    /**
     * @return string
     */
    public function getLine2(): ?string
    {
        return $this->addressLines ? $this->addressLines->getAddressLine2() : null;
    }

    /**
     * @return string
     */
    public function getLine3(): ?string
    {
        return $this->addressLines ? $this->addressLines->getAddressLine3() : null;
    }

    /**
     * @return string
     */
    public function getTown(): ?string
    {
        return $this->town;
    }

    /**
     * @return string
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getCounty(): ?string
    {
        return $this->county;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return float
     */
    public function getLongitude(): ?float
    {
        return $this->coordinates ? $this->coordinates->getLongitude() : null;
    }

    /**
     * @return float
     */
    public function getLatitude(): ?float
    {
        return $this->coordinates ? $this->coordinates->getLatitude() : null;
    }

    /**
     * @return string
     */
    public function getFullPremise(): ?string
    {
        return $this->fullPremise;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function jsonSerialize()
    {
        return [
            'fullpremise' => $this->fullPremise,
            'building_number' => $this->buildingName ? $this->buildingName->getBuildingNumber() : null,
            'building_name' => $this->buildingName ? $this->buildingName->getBuildingName() : null,
            'thoroughfare' => $this->thoroughfare,
            'line_1' => $this->addressLines ? $this->addressLines->getAddressLine1() : null,
            'line_2' => $this->addressLines ? $this->addressLines->getAddressLine2() : null,
            'line_3' => $this->addressLines ? $this->addressLines->getAddressLine3() : null,
            'town' => $this->town,
            'postcode' => $this->postcode,
            'district' => $this->city,
            'county' => $this->county,
            'country' => $this->country,
            'longitude' => $this->getLongitude(),
            'latitude' => $this->getLatitude(),
        ];
    }
}
