<?php

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\File;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Model\File
 */
class FileTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_have_metadata()
    {
        $file = new class('') extends File {
            public function getContent()
            {
            }
        };

        $file->getMetadata()['foo'] = 'test';
        $this->assertEquals('test', $file->getMetadata()['foo']);
    }
}
