<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\OverrideConfigFromCLI;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Module\Config\Step\OverrideConfigFromCLI
 */
class OverrideConfigFromCLITest extends TestCase
{
    /**
     * @test
     */
    public function should_override_title_if_specified()
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';
        $project->metadata['cliConfig'] = ['title=bar'];

        $logger = $this->createMock("Psr\Log\LoggerInterface");

        $step = new OverrideConfigFromCLI($logger);
        $step->__invoke($project);

        $this->assertEquals('bar', $project->metadata['title']);
    }

    /**
     * @test
     */
    public function should_not_override_title_if_not_specified()
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';
        $project->metadata['cliConfig'] = [];

        $logger = $this->createMock("Psr\Log\LoggerInterface");

        $step = new OverrideConfigFromCLI($logger);
        $step->__invoke($project);

        $this->assertEquals('foo', $project->metadata['title']);
    }

    /**
     * @test
     */
    public function should_not_override_title_if_no_cliConfig()
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';

        $logger = $this->createMock("Psr\Log\LoggerInterface");

        $step = new OverrideConfigFromCLI($logger);
        $step->__invoke($project);

        $this->assertEquals('foo', $project->metadata['title']);
    }

    /**
     * @test
     */
    public function should_override_template_url()
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';
        $project->metadata['template'] = ['url' => 'https://github.com/template.git'];
        $project->metadata['cliConfig'] = ['template.url=https://gitlab.com/template.git'];
        $logger = $this->createMock("Psr\Log\LoggerInterface");
        $step = new OverrideConfigFromCLI($logger);

        $step->__invoke($project);

        $this->assertEquals('foo', $project->metadata['title']);
        $this->assertEquals('https://gitlab.com/template.git', $project->metadata['template']['url']);
    }
}
