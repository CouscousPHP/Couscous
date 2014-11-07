<?php

namespace Couscous\Tests\UnitTest\Step\Markdown;

use Couscous\Model\LazyFile;
use Couscous\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Step\Markdown\ProcessMarkdownFileName;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Couscous\Step\Markdown\ProcessMarkdownFileName
 */
class ProcessMarkdownFileNameTest extends \PHPUnit_Framework_TestCase
{
    public function testRenameExtension()
    {
        $this->assertFileRenamed('test.html', 'test.md');
    }

    public function testRenameReadme()
    {
        $this->assertFileRenamed('index.html', 'README.md');
    }

    public function testRenameReadmeInSubDirectory()
    {
        $this->assertFileRenamed('foo/index.html', 'foo/README.md');
    }

    public function testNonMarkdownFileNotRenamed()
    {
        $file       = new LazyFile('foo.txt', 'foo.txt');
        $repository = new Repository('foo', 'bar');
        $repository->addFile($file);

        $step = new ProcessMarkdownFileName();
        $step->__invoke($repository, new NullOutput());

        $files = $repository->getFiles();

        $this->assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        $this->assertEquals('foo.txt', $newFile->relativeFilename);
        $this->assertSame($newFile, $file);
    }

    private function assertFileRenamed($expected, $filename)
    {
        $file       = new MarkdownFile($filename, '');
        $repository = new Repository('foo', 'bar');
        $repository->addFile($file);

        $step = new ProcessMarkdownFileName();
        $step->__invoke($repository, new NullOutput());

        $files = $repository->getFiles();

        $this->assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        $this->assertEquals($expected, $newFile->relativeFilename);
    }
}
