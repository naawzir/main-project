<?php declare(strict_types=1);

namespace Tests\Unit\ReferenceGenerator;

use App\ReferenceGenerator\Reference;
use Tests\TestCase;

class ReferenceTest extends TestCase
{
    public function testSettingAndRetrievingReturnsSameValue(): void
    {
        $reference = new Reference(5000);
        $this->assertEquals('5000', $reference->toString());

        $reference = new Reference('hello');
        $this->assertEquals('hello', $reference->toString());
    }
}
