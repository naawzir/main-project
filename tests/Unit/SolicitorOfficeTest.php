<?php

namespace Tests\Unit;

use App\Solicitor;
use App\SolicitorOffice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SolicitorOfficeTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
        SolicitorOffice::getQuery()->delete();
    }

    /** @test */
    public function generates_a_slug_when_created()
    {
        $office = factory(SolicitorOffice::class)->make([
            'slug' => null,
        ]);

        $office->save();

        $this->assertNotNull($office->slug);
    }

    /** @test */
    public function generates_a_slug_when_the_slug_is_empty()
    {
        $office = factory(SolicitorOffice::class)->make([
            'slug' => '',
        ]);

        $office->save();

        $this->assertNotEquals('', $office->slug);
    }

    /** @test */
    public function does_not_generate_a_slug_if_there_is_already_one()
    {
        $office = factory(SolicitorOffice::class)->make();
        $uuid = $office->slug;

        $office->save();

        $this->assertEquals($uuid, $office->slug);
    }

    /** @test */
    public function Onboarding__AsBDM__ReturnsACollectionOfOffices()
    {
        $office = factory(SolicitorOffice::class)->create([
            'solicitor_id' => function () {
                return factory(Solicitor::class)->create()->id;
            },
            'status' => 'Pending',
        ]);

        $offices = SolicitorOffice::onboarding(true)->get();

        $this->assertCount(1, $offices);
        $this->assertEquals($office->slug, $offices[0]->slug);
    }

    /**
     * @test
     * @dataProvider notAsBdmStatusProvider
     */
    public function Onboarding__NotAsBDM__FindsOfficesWithTheStatus($status)
    {
        $office = factory(SolicitorOffice::class)->create([
            'solicitor_id' => function () {
                return factory(Solicitor::class)->create()->id;
            },
            'status' => $status,
        ]);

        $offices = SolicitorOffice::onboarding(false)->get();

        $this->assertCount(1, $offices);
        $this->assertEquals($office->slug, $offices[0]->slug);
    }

    public function notAsBdmStatusProvider()
    {
        return [
            'Onboarding' => ['Onboarding'],
            'SentToTM' => ['SentToTM'],
        ];
    }
}
