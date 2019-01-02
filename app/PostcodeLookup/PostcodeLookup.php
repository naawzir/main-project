<?php declare(strict_types=1);

namespace App\PostcodeLookup;

interface PostcodeLookup
{
    /**
     * @param string $postcode
     * @return AddressIterator
     * @throws PostcodeLookupException
     */
    public function lookupByPostcode(string $postcode): AddressIterator;
}
