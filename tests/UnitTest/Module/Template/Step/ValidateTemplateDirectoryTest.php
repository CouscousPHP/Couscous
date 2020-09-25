<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Module\Template\Step\ValidateTemplateDirectory;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Module\Template\Step\ValidateTemplateDirectory
 */
class ValidateTemplateDirectoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_use_the_default_directory_if_no_directory_is_set(): void
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $step->__invoke($project);

        self::assertEquals('/foo/website', $project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_complete_a_relative_path(): void
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = 'bar';
        $step->__invoke($project);

        self::assertEquals('/foo/bar', $project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_not_change_an_absolute_path(): void
    {
        $step = new ValidateTemplateDirectory($this->createFilesystem());
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = '/hello/world';
        $step->__invoke($project);

        self::assertEquals('/hello/world', $project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_error_with_an_invalid_relative_path(): void
    {
        $this->expectExceptionMessage("The template directory '/foo/bar' doesn't exist");
        $this->expectException(\RuntimeException::class);

        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = 'bar';
        $step->__invoke($project);
    }

    /**
     * @test
     */
    public function it_should_error_with_an_invalid_absolute_path(): void
    {
        $this->expectExceptionMessage("The template directory '/hello/world' doesn't exist");
        $this->expectException(\RuntimeException::class);

        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $project->metadata['template.directory'] = '/hello/world';
        $step->__invoke($project);
    }

    /**
     * @test
     */
    public function it_should_error_with_an_invalid_default_path(): void
    {
        $this->expectExceptionMessage("The template directory '/foo/website' doesn't exist");
        $this->expectException(\RuntimeException::class);

        $step = new ValidateTemplateDirectory($this->createFilesystem(false));
        $project = new MockProject();
        $project->sourceDirectory = '/foo';
        $step->__invoke($project);
    }

    private function createFilesystem(bool $existsShouldReturn = true): Filesystem
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
