<?php

namespace Tests\Feature;

use Tests\TestCase;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssertSeeTest extends TestCase
{
    protected function setUp()
    {
        $this->markTestSkipped(
            'Feature tests will be imported as mocha/chai tests instead.'
        );
    }

    public function testHome()
    {
        $response = $this->get('home');

        $response->assertSee('home');
    }

    public function testCaseListPage()
    {
        $response = $this->get('cases');

        $response->assertSee('CASES');
    }

    public function testSolicitorListPage()
    {
        $response = $this->get('solicitors');

        $response->assertSee('SOLICITOR LIST');
    }

    public function testLoginPage()
    {
        $response = $this->get('login');

        $response->assertSee('Solicitor Login Page');
    }

    public function testForgotPasswordPage()
    {
        $response = $this->get('password/forgot');

        $response->assertSee('Forgot Your Password');
    }

    public function testChangePasswordPage()
    {
        $user = Auth::loginUsingId(13582);

        $response = $this->get('password/change');

        $response->assertSee('Change Password');
    }

/*    public function testLogoutPage()
    {
        $response = $this->get('logout');

    }*/
}
