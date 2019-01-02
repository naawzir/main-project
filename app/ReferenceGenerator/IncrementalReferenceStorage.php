<?php declare(strict_types=1);

namespace App\ReferenceGenerator;

interface IncrementalReferenceStorage
{
    public function storeNewReference(Reference $reference): void;
    public function loadLastReference(): Reference;
}
