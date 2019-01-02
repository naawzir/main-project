<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class NavigationRoutesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @dataProvider routesProvider
     */
    public function testNavigationLinks($role, $route)
    {
        $user = factory(User::class)->state('role:' . $role)->create();

        $this->actingAs($user)
            ->get($route)
            ->seeStatusCode(Response::HTTP_OK);
    }

    public function routesProvider()
    {
        return [
            ['account-manager', 'staff'],
            ['account-manager', 'branch'],
            ['account-manager', 'disbursements'],
            ['account-manager', 'customers'],
            ['account-manager', 'feedback/service'],
            ['account-manager', 'solicitors'],
            ['panel-manager', 'cases/update-requests'],
        ];
    }
}
