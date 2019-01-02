<?php declare(strict_types=1);

namespace Tests\Unit\PostcodeLookup;

use App\PostcodeLookup\PostcodeLookupException;
use Tests\TestCase;

class PostcodeLookupExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown(): void
    {
        $this->expectException(PostcodeLookupException::class);

        throw new PostcodeLookupException('Should be thrown');
    }
}
