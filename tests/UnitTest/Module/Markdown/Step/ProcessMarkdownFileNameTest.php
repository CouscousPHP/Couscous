<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Markdown\Step;

use Couscous\Model\LazyFile;
use Couscous\Model\Metadata;
use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Module\Markdown\Step\ProcessMarkdownFileName;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Module\Markdown\Step\ProcessMarkdownFileName
 */
class ProcessMarkdownFileNameTest extends TestCase
{
    public function testRenameExtension(): void
    {
        $this->assertFileRenamed('test.html', 'test.md');
    }

    public function testRenameUppercase(): void
    {
        $this->assertFileRenamed('contributing.html', 'CONTRIBUTING.md');
    }

    public function testRenameReadme(): void
    {
        $this->assertFileRenamed('index.html', 'README.md');
    }

    public function testRenameIndexFile(): void
    {
        $this->assertFileRenamed('foo/index.html', 'foo/index.md', true);
        $this->assertFileRenamed('readme.html', 'README.md', true);
    }

    public function testRenameReadmeInSubDirectory(): void
    {
        $this->assertFileRenamed('foo/index.html', 'foo/README.md');
    }

    public function testRenameReadmeMessyFilename(): void
    {
        $this->assertFileRenamed('some-other_complicated.file.html', 'SOME-OTHER_complicated.FILE.md');
    }

    public function testNonMarkdownFileNotRenamed(): void
    {
        $file = new LazyFile('foo.txt', 'foo.txt');
        $project = new Project('foo', 'bar');
        $project->addFile($file);

        $step = new ProcessMarkdownFileName();
        $step->__invoke($project);

        $files = $project->getFiles();

        self::assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        self::assertEquals('foo.txt', $newFile->relativeFilename);
        self::assertSame($newFile, $file);
    }

    private function assertFileRenamed(string $expected, string $filename, bool $meta = false): void
    {
        $file = new MarkdownFile($filename, '');
        $project = new Project('foo', 'bar');
        $project->addFile($file);

        if ($meta) {
            $project->metadata = new Metadata();
            $project->metadata->setMany(['template' => ['index' => 'foo/index.md']]);
        }

        $step = new ProcessMarkdownFileName();
        $step->__invoke($project);

        $files = $project->getFiles();

        self::assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        self::assertEquals($expected, $newFile->relativeFilename);
    }
}
