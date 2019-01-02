<?php

namespace Tests\Feature;

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Tests\CreatesApplication;

class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $baseUrl;

    protected function setUp()
    {
        parent::setUp();
        $this->baseUrl = config('app.url');
    }
}
