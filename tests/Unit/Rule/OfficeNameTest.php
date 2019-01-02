<?php

namespace Tests\Unit\Rule;

use App\Rules\OfficeName;
use App\Solicitor;
use App\SolicitorOffice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OfficeNameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function Passes__NameDoesNotExists__ReturnsTrue()
    {
        $solicitor = factory(Solicitor::class)->create();

        $rule = new OfficeName($solicitor);

        $this->assertTrue($rule->passes('', 'test'));
    }

    /** @test */
    public function Passes__NameAlreadyExists__ReturnsTrue()
    {
        $solicitor = factory(Solicitor::class)->create();
        factory(SolicitorOffice::class)->create([
            'solicitor_id' => $solicitor->id,
            'office_name' => 'test',
        ]);

        $rule = new OfficeName($solicitor);

        $this->assertFalse($rule->passes('', 'test'));
    }

    /** @test */
    public function Passes__TheGivenOffceNameIsIgnored__ReturnsTrue()
    {
        $solicitor = factory(Solicitor::class)->create();
        $office = factory(SolicitorOffice::class)->create([
            'solicitor_id' => $solicitor->id,
            'office_name' => 'test',
        ]);

        $rule = new OfficeName($solicitor, $office);

        $this->assertTrue($rule->passes('', 'test'));
    }
}
