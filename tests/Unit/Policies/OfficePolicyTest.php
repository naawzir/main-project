<?php

namespace Tests\Unit\Policies;

use App\Policies\OfficePolicy;
use App\SolicitorOffice;
use App\User;
use Tests\TestCase;

class OfficePolicyTest extends TestCase
{
    /** @test */
    public function SubmitToPm()
    {
        $user = factory(User::class)->state('role:bdm')->make();

        $policy = new OfficePolicy();

        $this->assertTrue($policy->submitToPM($user, $this->createMock(SolicitorOffice::class)));
    }

    /** @test */
    public function SubmitToTm()
    {
        $user = factory(User::class)->state('role:panel-manager')->make();

        $policy = new OfficePolicy();

        $this->assertTrue($policy->submitToTM($user, $this->createMock(SolicitorOffice::class)));
    }

    /** @test */
    public function SubmitToMarketplace()
    {
        $user = factory(User::class)->state('role:panel-manager')->make();

        $policy = new OfficePolicy();

        $this->assertTrue($policy->submitToMarketplace($user, $this->createMock(SolicitorOffice::class)));
    }
}
