<?php

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\File;
use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Module\Template\Model\HtmlFile;

/**
 * @covers \Couscous\Model\Project
 */
class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_contain_files()
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
        $this->assertSame($expected, $project->getFiles());

        $project->removeFile($file1);
        $this->assertSame(['file2' => $file2], $project->getFiles());

        $project->removeFile($file2);
        $this->assertSame([], $project->getFiles());
    }

    /**
     * @test
     */
    public function replace_should_replace_files()
    {
        $project = new Project('source', 'target');

        $file1 = $this->createFile('file1');
        $file2 = $this->createFile('file2');

        $project->addFile($file1);
        $this->assertSame(['file1' => $file1], $project->getFiles());

        $project->replaceFile($file1, $file2);
        $this->assertSame(['file2' => $file2], $project->getFiles());
    }

    /**
     * @test
     */
    public function it_should_return_files_by_type()
    {
        $project = new Project('source', 'target');

        $file1 = new MarkdownFile('file1', 'Hello');
        $file2 = new HtmlFile('file2', 'Hello');

        $project->addFile($file1);
        $project->addFile($file2);

        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');
        $this->assertSame(['file1' => $file1], $markdownFiles);

        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');
        $this->assertSame(['file2' => $file2], $htmlFiles);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|File
     */
    private function createFile($name)
    {
        return $this->getMockForAbstractClass('Couscous\Model\File', [$name]);
    }
}
