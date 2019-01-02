<?php declare(strict_types=1);

namespace App\PostcodeLookup;

class AddressLines
{
    private $addressline1;
    private $addressline2;
    private $addressline3;

    public function __construct(?string $addressline1, ?string $addressline2, ?string $addressline3)
    {
        $this->addressline1 = $addressline1;
        $this->addressline2 = $addressline2;
        $this->addressline3 = $addressline3;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressline1;
    }
    public function getAddressLine2(): ?string
    {
        return $this->addressline2;
    }
    public function getAddressLine3(): ?string
    {
        return $this->addressline3;
    }
}
