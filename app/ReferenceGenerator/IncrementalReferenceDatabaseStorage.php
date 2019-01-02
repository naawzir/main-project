<?php declare(strict_types=1);

namespace App\ReferenceGenerator;

use Illuminate\Database\DatabaseManager;

class IncrementalReferenceDatabaseStorage implements IncrementalReferenceStorage
{
    /**
     * @var DatabaseManager
     */
    private $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    public function storeNewReference(Reference $reference): void
    {
        $this->database
            ->table('last_case_reference')
            ->update(['reference' => $reference->toString(), 'date_updated' => time()]);
    }

    public function loadLastReference(): Reference
    {
        $reference = $this->database
            ->table('last_case_reference')
            ->select('reference')
            ->orderBy('reference', 'DESC')
            ->limit(1)
            ->get(['last_case_reference']);

        if ($reference->isEmpty()) {
            return new Reference('0');
        }

        return new Reference($reference->first()->reference);
    }
}
