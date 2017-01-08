<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\OverrideConfigFromCLI;
use Couscous\Tests\UnitTest\Mock\MockProject;

/**
 * @covers \Couscous\Module\Config\Step\OverrideConfigFromCLI
 */
class OverrideConfigFromCLITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function should_override_title_if_specified()
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';
        $project->metadata['cliConfig'] = ['title=bar'];

        $logger = $this->getMock("Psr\Log\LoggerInterface");

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

        $logger = $this->getMock("Psr\Log\LoggerInterface");

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

        $logger = $this->getMock("Psr\Log\LoggerInterface");

        $step = new OverrideConfigFromCLI($logger);
        $step->__invoke($project);

        $this->assertEquals('foo', $project->metadata['title']);
    }
}
