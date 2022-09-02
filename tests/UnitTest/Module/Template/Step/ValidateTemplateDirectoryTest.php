<?php

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Module\Template\Step\ValidateTemplateDirectory;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Module\Template\Step\ValidateTemplateDirectory
 */
class ValidateTemplateDirectoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_use_the_default_directory_if_no_directory_is_set()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $step->__invoke($project);

        $this->assertEquals('/foo/website', $project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_complete_a_relative_path()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = 'bar';
        $step->__invoke($project);

        $this->assertEquals('/foo/bar', $project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_not_change_an_absolute_path()
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = '/hello/world';
        $step->__invoke($project);

        $this->assertEquals('/hello/world', $project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_error_with_an_invalid_relative_path()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("The template directory '/foo/bar' doesn't exist");

        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = 'bar';
        $step->__invoke($project);
    }

    /**
     * @test
     */
    public function it_should_error_with_an_invalid_absolute_path()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("The template directory '/hello/world' doesn't exist");

        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = '/hello/world';
        $step->__invoke($project);
    }

    /**
     * @test
     *
     *
     */
    public function it_should_error_with_an_invalid_default_path()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("The template directory '/foo/website' doesn't exist");

        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $step->__invoke($project);
    }

    private function createFilesystem($existsShouldReturn = true): Filesystem
    {
        return new class($existsShouldReturn) extends Filesystem {
            public function __construct($existsShouldReturn)
            {
                $this->existsShouldReturn = $existsShouldReturn;
            }
            public function exists($files)
            {
                return $this->existsShouldReturn;
            }
        };
    }
}
