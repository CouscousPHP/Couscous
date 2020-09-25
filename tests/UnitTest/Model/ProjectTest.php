<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\File;
use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Module\Template\Model\HtmlFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Model\Project
 */
class ProjectTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_contain_files(): void
    {
        $project = new Project('source', 'target');

        $file1 = $this->createFile('file1');
        $file2 = $this->createFile('file2');

        $project->addFile($file1);
        $project->addFile($file2);
        $expected = [
            'file1' => $file1,
            'file2' => $file2,
        ];
        self::assertSame($expected, $project->getFiles());

        $project->removeFile($file1);
        self::assertSame(['file2' => $file2], $project->getFiles());

        $project->removeFile($file2);
        self::assertSame([], $project->getFiles());
    }

    /**
     * @test
     */
    public function replace_should_replace_files(): void
    {
        $project = new Project('source', 'target');

        $file1 = $this->createFile('file1');
        $file2 = $this->createFile('file2');

        $project->addFile($file1);
        self::assertSame(['file1' => $file1], $project->getFiles());

        $project->replaceFile($file1, $file2);
        self::assertSame(['file2' => $file2], $project->getFiles());
    }

    /**
     * @test
     */
    public function it_should_return_files_by_type(): void
    {
        $project = new Project('source', 'target');

        $file1 = new MarkdownFile('file1', 'Hello');
        $file2 = new HtmlFile('file2', 'Hello');

        $project->addFile($file1);
        $project->addFile($file2);

        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');
        self::assertSame(['file1' => $file1], $markdownFiles);

        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');
        self::assertSame(['file2' => $file2], $htmlFiles);
    }

    /**
     * @return MockObject&File
     */
    private function createFile(string $name): MockObject
    {
        return $this->getMockForAbstractClass(File::class, [$name]);
    }
}
