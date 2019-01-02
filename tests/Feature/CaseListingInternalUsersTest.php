<?php
/**
 * Created by PhpStorm.
 * User: rizwaan.khan
 * Date: 18/07/2018
 * Time: 09:38
 */

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseListingInternalUsersTest extends TestCase
{
    protected function setUp()
    {
        $this->markTestSkipped(
            'Feature tests will be imported as mocha/chai tests instead.'
        );
    }
    
    public function testCaseListingFiltersInternalUsers()
    {
        $this->get('cases')
            ->assertSee('View')
            ->assertSee('Status')
            ->assertSee('Agent')
            ->assertSee('Transaction');
    }

    // I've had to give the table header elements in 'cases/index.blade.php' an ID for this to work
    public function testCaseListingTableHeaderInternalUsers()
    {
        $tableHeader = [

            'date_created',
            'reference',
            'status',
            'type',
            'address',
            'solicitor',
        ];

        $this->get('cases')->assertSeeInOrder($tableHeader);
    }
}
