<?php

namespace Couscous\tests\UnitTest\Module\Markdown\Step;

use Couscous\Model\LazyFile;
use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Module\Markdown\Step\ProcessMarkdownFileName;

/**
 * @covers \Couscous\Module\Markdown\Step\ProcessMarkdownFileName
 */
class ProcessMarkdownFileNameTest extends \PHPUnit_Framework_TestCase
{
    public function testRenameExtension()
    {
        $this->assertFileRenamed('test.html', 'test.md');
    }

    public function testRenameUppercase()
    {
        $this->assertFileRenamed('contributing.html', 'CONTRIBUTING.md');
    }

    public function testRenameReadme()
    {
        $this->assertFileRenamed('index.html', 'README.md');
    }

    public function testRenameReadmeInSubDirectory()
    {
        $this->assertFileRenamed('foo/index.html', 'foo/README.md');
    }

    public function testRenameReadmeMessyFilename()
    {
        $this->assertFileRenamed('some-other_complicated.file.html', 'SOME-OTHER_complicated.FILE.md');
    }

    public function testNonMarkdownFileNotRenamed()
    {
        $file = new LazyFile('foo.txt', 'foo.txt');
        $project = new Project('foo', 'bar');
        $project->addFile($file);

        $step = new ProcessMarkdownFileName();
        $step->__invoke($project);

        $files = $project->getFiles();

        $this->assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        $this->assertEquals('foo.txt', $newFile->relativeFilename);
        $this->assertSame($newFile, $file);
    }

    private function assertFileRenamed($expected, $filename)
    {
        $file = new MarkdownFile($filename, '');
        $project = new Project('foo', 'bar');
        $project->addFile($file);

        $step = new ProcessMarkdownFileName();
        $step->__invoke($project);

        $files = $project->getFiles();

        $this->assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        $this->assertEquals($expected, $newFile->relativeFilename);
    }
}
