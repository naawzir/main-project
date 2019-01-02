<?php declare(strict_types=1);

namespace App\ReferenceGenerator;

class IncrementalReferenceGenerator implements ReferenceGenerator
{
    /**
     * @var IncrementalReferenceStorage
     */
    private $referenceStorage;
    private $lastGeneratedReference;

    /**
     * IncrementalReferenceGenerator constructor.
     * @param IncrementalReferenceStorage $referenceStorage
     */
    public function __construct(IncrementalReferenceStorage $referenceStorage)
    {
        $this->referenceStorage = $referenceStorage;
    }

    public function generateNextReference(): void
    {
        $reference = $this->referenceStorage->loadLastReference()->toString();
        ++$reference;
        $newReference = new Reference($reference);
        $this->referenceStorage->storeNewReference($newReference);
        $this->lastGeneratedReference = $newReference;
    }

    public function getGeneratedReference(): Reference
    {
        if (!$this->lastGeneratedReference) {
            throw new ReferenceNotGeneratedException(
                'Call generateNextReference() before attempting to retrieve a generated reference.'
            );
        }
        return $this->lastGeneratedReference;
    }
}
