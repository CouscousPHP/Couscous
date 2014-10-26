<?php

namespace Piwik\Tests\UnitTest\Step\Markdown;

use Couscous\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Step\Markdown\ProcessMarkdownFileName;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Couscous\Step\Markdown\ProcessMarkdownFileName
 */
class ProcessMarkdownFileNameTest extends \PHPUnit_Framework_TestCase
{
    public function testRenameReadme()
    {
        $file = new MarkdownFile('README.md', '');
        $repository = new Repository('foo', 'bar');
        $repository->addFile($file);

        $step = new ProcessMarkdownFileName();
        $step->__invoke($repository, new NullOutput());

        $files = $repository->getFiles();

        $this->assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        $this->assertEquals('index.html', $newFile->relativeFilename);
    }

    public function testRenameReadmeInSubDirectory()
    {
        $file = new MarkdownFile('foo/README.md', '');
        $repository = new Repository('foo', 'bar');
        $repository->addFile($file);

        $step = new ProcessMarkdownFileName();
        $step->__invoke($repository, new NullOutput());

        $files = $repository->getFiles();

        $this->assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        $this->assertEquals('foo/index.html', $newFile->relativeFilename);
    }
}
