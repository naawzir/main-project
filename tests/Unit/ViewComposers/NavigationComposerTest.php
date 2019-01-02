<?php

namespace Tests\Unit\ViewComposers;

use App\User;
use App\ViewComposers\Composer;
use App\ViewComposers\NavigationComposer;
use Illuminate\View\View;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\MockObject\MockObject;

class NavigationComposerTest extends TestCase
{
    use DatabaseTransactions;
    /** @var Composer */
    private $composer;

    protected function setUp()
    {
        parent::setUp();
        $this->composer = $this->app->make(NavigationComposer::class);
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function Compose__AddsTheNavVariable($role)
    {
        $user = factory(User::class)->state('role:' . $role)->create();
        $this->actingAs($user);

        $this->view->expects($this->once())
            ->method('with')
            ->with('nav', 'navigation.' . $role);

        $this->composer->compose($this->view);
    }

    public function roleProvider(): array
    {
        $roles = [
            'account-manager',
            'account-manager-lead',
            'agent',
            'bdm',
            'branch-manager',
            'business-owner',
            'director',
            'panel-manager',
            'solicitor',
            'superuser',
        ];

        return array_map(function ($role) {
            return [$role];
        }, array_combine($roles, $roles));
    }
}
