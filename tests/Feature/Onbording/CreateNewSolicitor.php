<?php

namespace Tests\Feature\Onbording;

use App\Address;
use App\Solicitor;
use App\SolicitorOffice;
use App\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\TestCase;

class CreateNewSolicitor extends TestCase
{
    use DatabaseTransactions;
    /** @var Generator */
    private $faker;
    /** @var User */
    private $user;

    protected function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create('en_GB');
        $this->user = factory(User::class)->state('role:bdm')->create();
    }

    /** @test */
    public function a_bdm_can_create_a_new_solicitor()
    {
        $address = factory(Address::class)->make();
        $office = factory(SolicitorOffice::class)->make([
            'capacity' => $this->faker->randomNumber,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
        ]);
        $solicitor = factory(Solicitor::class)->make();

        $this->startSession();
        $this->actingAs($this->user)
            ->visit(route('solicitors.onbording.create'))
            ->type($solicitor->name, '#name')
            ->type($office->email, '#email')
            ->type($office->phone, '#phone')
            ->type($office->office_name, '#office_name')
            ->type($address->building_name, '#building_name')
            ->type($address->address_line_1, '#address_line_1')
            ->type($address->postcode, '#postcode')
            ->type($address->town, '#town')
            ->type($address->county, '#county')
            ->type($office->capacity, '#capacity')
            ->press('Save');

        $this->seeInDatabase('addresses', [
            'building_name' => $address->building_name,
            'address_line_1' => $address->address_line_1,
            'postcode' => $address->postcode,
        ]);
        $this->seeInDatabase('solicitors', [
            'name' => $solicitor->name,
            'email' => $office->email,
        ]);
        $this->seeInDatabase('solicitor_offices', [
            'office_name' => $office->office_name,
            'phone' => $office->phone,
            'email' => $office->email,
            'capacity' => $office->capacity,
        ]);
    }

    /** @test */
    public function if_no_office_name_is_given_the_solicitor_name_is_used()
    {
        $address = factory(Address::class)->make();
        $office = factory(SolicitorOffice::class)->make([
            'capacity' => $this->faker->randomNumber,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
        ]);
        $solicitor = factory(Solicitor::class)->make();

        $this->startSession();
        $this->actingAs($this->user)
            ->visit(route('solicitors.onbording.create'))
            ->type($solicitor->name, '#name')
            ->type($office->email, '#email')
            ->type($office->phone, '#phone')
            ->type($address->building_name, '#building_name')
            ->type($address->address_line_1, '#address_line_1')
            ->type($address->postcode, '#postcode')
            ->type($address->town, '#town')
            ->type($address->county, '#county')
            ->type($office->capacity, '#capacity')
            ->press('Save');

        $this->seeInDatabase('solicitors', [
            'name' => $solicitor->name,
        ]);
        $this->seeInDatabase('solicitor_offices', [
            'office_name' => $solicitor->name,
        ]);
    }
}
