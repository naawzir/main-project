<?php declare(strict_types=1);

namespace App\PostcodeLookup;

use Academe\IdealPostcodes\Postcode;

/**
 * Class IdealPostcodeLookup
 * @package App\PostcodeLookup
 * TODO: Create transformer class which takes lookup results and converts to Address.
 * This class is doing too much.
 */
class IdealPostcodeLookup implements PostcodeLookup
{
    private $postcodeLookup;

    public function __construct(Postcode $postcodeLookup)
    {
        $this->postcodeLookup = $postcodeLookup;
    }

    public function lookupByPostcode(string $postcode): AddressIterator
    {
        $results = $this->postcodeLookup->getAddresses($postcode);

        if ($this->postcodeLookup->getCode() !== 2000) {
            throw new PostcodeLookupException(sprintf(
                'Unable to lookup postcode "%s" from Ideal Postcodes - [%s] %s',
                $postcode,
                $this->postcodeLookup->getCode(),
                $this->postcodeLookup->getMessage()
            ));
        }

        $addressArray = [];
        foreach ($results as $result) {
            $coordinates = null;
            $addressLines = null;
            $buildingName = null;

            $buildingName = new BuildingName(
                $result->building_number ?? null,
                $result->building_name ?? null
            );

            $addressLines = new AddressLines(
                $result->line_1 ?? null,
                $result->line_2 ?? null,
                $result->line_3 ?? null
            );

            $coordinates = new GeoCoordinates($result->longitude, $result->latitude);

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

            $addressArray = array_filter($addressArray);
        }
        return new AddressIterator(...$addressArray);
    }
}
