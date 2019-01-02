<?php declare(strict_types=1);

namespace App\PostcodeLookup;

interface PostcodeLookupCache
{
    /**
     * @param string $postcode
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function cleanCacheForPostcode(string $postcode): void;
}
