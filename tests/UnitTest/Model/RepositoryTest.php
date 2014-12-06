<?php

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\File;
use Couscous\Model\File\HtmlFile;
use Couscous\Model\File\MarkdownFile;
use Couscous\Model\Repository;

/**
 * @covers \Couscous\Model\Repository
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_contain_files()
    {
        $repository = new Repository('source', 'target');

        $file1 = $this->createFile('file1');
        $file2 = $this->createFile('file2');

        $repository->addFile($file1);
        $repository->addFile($file2);
        $expected = [
            'file1' => $file1,
            'file2' => $file2,
        ];
        $this->assertSame($expected, $repository->getFiles());

        $repository->removeFile($file1);
        $this->assertSame(['file2' => $file2], $repository->getFiles());

        $repository->removeFile($file2);
        $this->assertSame([], $repository->getFiles());
    }

    /**
     * @test
     */
    public function replace_should_replace_files()
    {
        $repository = new Repository('source', 'target');

        $file1 = $this->createFile('file1');
        $file2 = $this->createFile('file2');

        $repository->addFile($file1);
        $this->assertSame(['file1' => $file1], $repository->getFiles());

        $repository->replaceFile($file1, $file2);;
        $this->assertSame(['file2' => $file2], $repository->getFiles());
    }

    /**
     * @test
     */
    public function it_should_return_files_by_type()
    {
        $repository = new Repository('source', 'target');

        $file1 = new MarkdownFile('file1', 'Hello');
        $file2 = new HtmlFile('file2', 'Hello');

        $repository->addFile($file1);
        $repository->addFile($file2);

        $markdownFiles = $repository->findFilesByType('Couscous\Model\File\MarkdownFile');
        $this->assertSame(['file1' => $file1], $markdownFiles);

        $htmlFiles = $repository->findFilesByType('Couscous\Model\File\HtmlFile');
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
