<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\CommandRunner\Git;
use Couscous\Module\Template\Step\FetchRemoteTemplate;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Module\Template\Step\FetchRemoteTemplate
 */
class FetchRemoteTemplateTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_skip_if_no_template_url(): void
    {
        $filesystem = $this->createMock(Filesystem::class);
        $git = $this->createMock(Git::class);

        $step = new FetchRemoteTemplate($filesystem, new NullLogger(), $git);

        $project = new MockProject();

        $git->expects(self::never())
            ->method(self::anything());

        $step->__invoke($project);

        self::assertNull($project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_clone_and_set_the_template_directory(): void
    {
        $filesystem = $this->createMock(Filesystem::class);
        $git = $this->createMock(Git::class);

        $step = new FetchRemoteTemplate($filesystem, new NullLogger(), $git);

        $project = new MockProject();
        $project->metadata['template.url'] = 'git://foo';

        $git->expects(self::once())
            ->method('cloneRepository')
            ->with('git://foo');

        $step->__invoke($project);

        self::assertNotNull($project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_not_clone_twice_if_regenerating(): void
    {
        $filesystem = $this->createMock(Filesystem::class);
        $git = $this->createMock(Git::class);

        $step = new FetchRemoteTemplate($filesystem, new NullLogger(), $git);

        $git->expects(self::once())
            ->method('cloneRepository')
            ->with('git://foo');

        // Calling once
        $project = new MockProject();
        $project->metadata['template.url'] = 'git://foo';
        $step->__invoke($project);
        self::assertNotNull($project->metadata['template.directory']);

        // Calling twice
        $project = new MockProject();
        $project->regenerate = true;
        $project->metadata['template.url'] = 'git://foo';
        $step->__invoke($project);
        self::assertNotNull($project->metadata['template.directory']);
    }
}
