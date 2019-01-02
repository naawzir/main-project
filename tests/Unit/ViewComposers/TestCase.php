<?php

namespace Tests\Unit\ViewComposers;

use Illuminate\Contracts\View\View;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** @var View|MockObject */
    protected $view;

    protected function setUp()
    {
        parent::setUp();
        $this->view = $this->createMock(View::class);
    }
}
