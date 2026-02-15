<?php

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\File;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(File::class)]
class FileTest extends TestCase
{
    #[Test]
    public function it_should_have_metadata()
    {
        $file = new class('') extends File {
            public function getContent(): string
            {
            }
        };

        $file->getMetadata()['foo'] = 'test';
        $this->assertEquals('test', $file->getMetadata()['foo']);
    }
}
