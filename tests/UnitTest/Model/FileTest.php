<?php

declare(strict_types=1);

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
    public function it_should_have_metadata(): void
    {
        $file = new class('') extends File {
            public function getContent(): string
            {
                return '';
            }
        };

        $file->getMetadata()['foo'] = 'test';
        self::assertEquals('test', $file->getMetadata()['foo']);
    }
}
