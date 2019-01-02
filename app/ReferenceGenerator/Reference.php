<?php declare(strict_types=1);

namespace App\ReferenceGenerator;

class Reference
{
    private $reference;

    public function __construct($reference)
    {
        $this->reference = $reference;
    }

    public function toString(): string
    {
        return (string) $this->reference;
    }
}
