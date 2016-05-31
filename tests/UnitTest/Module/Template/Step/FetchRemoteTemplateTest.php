<?php

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Module\Template\Step\FetchRemoteTemplate;
use Couscous\Tests\UnitTest\Mock\MockProject;
use Psr\Log\NullLogger;

/**
 * @covers \Couscous\Module\Template\Step\FetchRemoteTemplate
 */
class FetchRemoteTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_skip_if_no_template_url()
    {
        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $git = $this->getMock('Couscous\CommandRunner\Git', [], [], '', false);

        $step = new FetchRemoteTemplate($filesystem, new NullLogger(), $git);

        $project = new MockProject();

        $git->expects($this->never())
            ->method($this->anything());

        $step->__invoke($project);

        $this->assertNull($project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_clone_and_set_the_template_directory()
    {
        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $git = $this->getMock('Couscous\CommandRunner\Git', [], [], '', false);

        $step = new FetchRemoteTemplate($filesystem, new NullLogger(), $git);

        $project = new MockProject();
        $project->metadata['template.url'] = 'git://foo';

        $git->expects($this->once())
            ->method('cloneRepository')
            ->with('git://foo');

        $step->__invoke($project);

        $this->assertNotNull($project->metadata['template.directory']);
    }

    /**
     * @test
     */
    public function it_should_not_clone_twice_if_regenerating()
    {
        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $git = $this->getMock('Couscous\CommandRunner\Git', [], [], '', false);

        $step = new FetchRemoteTemplate($filesystem, new NullLogger(), $git);

        $git->expects($this->once())
            ->method('cloneRepository')
            ->with('git://foo');

        // Calling once
        $project = new MockProject();
        $project->metadata['template.url'] = 'git://foo';
        $step->__invoke($project);
        $this->assertNotNull($project->metadata['template.directory']);

        // Calling twice
        $project = new MockProject();
        $project->regenerate = true;
        $project->metadata['template.url'] = 'git://foo';
        $step->__invoke($project);
        $this->assertNotNull($project->metadata['template.directory']);
    }
}
