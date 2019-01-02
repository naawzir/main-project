<?php declare(strict_types=1);

namespace App\PostcodeLookup;

class BuildingName
{
    private $buildingNumber;
    private $buildingName;

    public function __construct(?string $buildingNumber, ?string $buildingName)
    {
        $this->buildingNumber = $buildingNumber;
        $this->buildingName = $buildingName;
    }

    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    public function getBuildingName(): ?string
    {
        return $this->buildingName;
    }
}
