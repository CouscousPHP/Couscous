<?php

namespace Couscous\Tests\UnitTest\Step\Template;

use Couscous\Step\Template\ValidateTemplateDirectory;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Step\Template\ValidateTemplateDirectory
 */
class ValidateTemplateDirectoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_use_the_default_directory_if_no_directory_is_set()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $repository = new MockRepository();
        $repository->sourceDirectory = '/foo';
        $step->__invoke($repository, new NullOutput());

        $this->assertEquals('/foo/website', $repository->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_complete_a_relative_path()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $repository = new MockRepository();
        $repository->sourceDirectory = '/foo';
        $repository->metadata['template.directory'] = 'bar';
        $step->__invoke($repository, new NullOutput());

        $this->assertEquals('/foo/bar', $repository->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_not_change_an_absolute_path()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $repository = new MockRepository();
        $repository->sourceDirectory = '/foo';
        $repository->metadata['template.directory'] = '/hello/world';
        $step->__invoke($repository, new NullOutput());

        $this->assertEquals('/hello/world', $repository->metadata['template.directory']);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The template directory '/foo/bar' doesn't exist
     */
    public function it_should_error_with_an_invalid_relative_path()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $repository = new MockRepository();
        $repository->sourceDirectory = '/foo';
        $repository->metadata['template.directory'] = 'bar';
        $step->__invoke($repository, new NullOutput());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The template directory '/hello/world' doesn't exist
     */
    public function it_should_error_with_an_invalid_absolute_path()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $repository = new MockRepository();
        $repository->sourceDirectory = '/foo';
        $repository->metadata['template.directory'] = '/hello/world';
        $step->__invoke($repository, new NullOutput());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The template directory '/foo/website' doesn't exist
     */
    public function it_should_error_with_an_invalid_default_path()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $repository = new MockRepository();
        $repository->sourceDirectory = '/foo';
        $step->__invoke($repository, new NullOutput());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private function createFilesystem($existsShouldReturn = true)
    {
        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem', ['exists']);
        $filesystem->expects($this->any())
            ->method('exists')
            ->willReturn($existsShouldReturn);
        return $filesystem;
    }
}
