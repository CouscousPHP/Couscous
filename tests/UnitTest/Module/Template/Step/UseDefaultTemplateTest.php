<?php

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Module\Template\Step\UseDefaultTemplate;
use Couscous\Tests\UnitTest\Mock\MockProject;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Module\Template\Step\UseDefaultTemplate
 */
class UseDefaultTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function use_default_template_when_no_template_configured()
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $project = new MockProject();
        $step->__invoke($project);

        $this->assertEquals(UseDefaultTemplate::DEFAULT_TEMPLATE_URL, $project->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_directory()
    {
        $step = new UseDefaultTemplate($this->createFilesystem(true));

        $project = new MockProject();
        $step->__invoke($project);

        $this->assertNull($project->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_directory_set()
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $project = new MockProject();
        $project->metadata['template.directory'] = 'foo';

        $step->__invoke($project);

        $this->assertNull($project->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_url_set()
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $project = new MockProject();
        $project->metadata['template.url'] = 'foo';

        $step->__invoke($project);

        // Assert URL isn't overridden
        $this->assertEquals('foo', $project->metadata['template.url']);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private function createFilesystem($return = false)
    {
        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $filesystem->expects($this->any())
            ->method('exists')
            ->willReturn($return);

        return $filesystem;
    }
}
