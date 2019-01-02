<?php declare(strict_types=1);

namespace App\PostcodeLookup;

use Illuminate\Contracts\Cache\Repository;

/**
 * Acts as a wrapper for another implementation of PostcodeLookup.
 * It will first search the local cache then fallback to the given implementation.
 * Any results returned from the implementation will be stored in cache.
 * @package App\PostcodeLookup
 */
class CacheWrapperPostcodeLookup implements PostcodeLookup, PostcodeLookupCache
{
    /**
     * @var PostcodeLookup
     */
    private $postcodeLookup;
    /**
     * @var Repository
     */
    private $cacheRepository;
    /**
     * @var string
     */
    private $cacheExpires;

    public function __construct(
        PostcodeLookup $postcodeLookup,
        Repository $cacheRepository,
        string $cacheExpires = '+1 year'
    ) {
        $this->postcodeLookup = $postcodeLookup;
        $this->cacheRepository = $cacheRepository;
        $this->cacheExpires = $cacheExpires;
    }

    private function normalisePostcode(string $postcode): string
    {
        return strtolower(str_replace(' ', '', $postcode));
    }

    private function getCacheKeyForPostcode(string $postcode): string
    {
        return 'address-lookup-' . $this->normalisePostcode($postcode);
    }

    public function lookupByPostcode(string $postcode): AddressIterator
    {
        $cacheKey = $this->getCacheKeyForPostcode($postcode);
        $results = $this->cacheRepository->get($cacheKey);

        if ($results) {
            $results = json_decode($results);

            $addressArray = [];
            foreach ($results as $result) {
                $coordinates = null;
                $addressLines = null;
                $buildingName = null;

                if ($result->building_number || $result->building_name) {
                    $buildingName = new BuildingName(
                        $result->building_number ?? null,
                        $result->building_name ?? null
                    );
                }

                if ($result->line_1 || $result->line_2 || $result->line_3) {
                    $addressLines = new AddressLines(
                        $result->line_1 ?? null,
                        $result->line_2 ?? null,
                        $result->line_3 ?? null
                    );
                }

                if ($result->longitude && $result->latitude) {
                    $coordinates = new GeoCoordinates($result->longitude, $result->latitude);
                }

                $addressArray[] = new Address(
                    $result->fullpremise,
                    $buildingName,
                    $result->thoroughfare,
                    $addressLines,
                    $result->town,
                    $result->postcode,
                    $result->district,
                    $result->county,
                    $result->country,
                    $coordinates
                );
            }

            return new AddressIterator(...$addressArray);
        }

        $results = $this->postcodeLookup->lookupByPostcode($postcode);

        try {
            $this->cacheRepository->put($cacheKey, json_encode($results), new \DateTimeImmutable($this->cacheExpires));
        } catch (\Exception $exception) {
            throw new PostcodeLookupException(sprintf(
                'Unable to store postcode "%s" into cache - %s',
                $postcode,
                $exception->getMessage()
            ));
        }
        return $results;
    }

    public function cleanCacheForPostcode(string $postcode): void
    {
        $this->cacheRepository->delete($this->getCacheKeyForPostcode($postcode));
    }
}
