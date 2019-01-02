<?php

namespace Tests\Unit\Dashboard;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Dashboard\AccountManager;
use App\User;

class AccountManagerTest extends TestCase
{
    /**
     * Do i need to include the Factory?
     *
     * @return void
     */
    public function testAccountManagerClass()
    {
        $dashboard = new AccountManager();
        $user = new User();
        $this->actingAs($user);

        $result = $dashboard->getData($user);
        $this->assertInternalType('array', $result);
    }
}
