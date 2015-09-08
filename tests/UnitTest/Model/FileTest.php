<?php

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\File;

/**
 * @covers \Couscous\Model\File
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_have_metadata()
    {
        /** @var File $file */
        $file = $this->getMock('Couscous\Model\File', ['getContent'], ['test.md']);

        $file->getMetadata()['foo'] = 'test';
        $this->assertEquals('test', $file->getMetadata()['foo']);
    }
}
