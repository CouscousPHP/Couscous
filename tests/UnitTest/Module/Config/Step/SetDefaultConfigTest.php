<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\SetDefaultConfig;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Module\Config\Step\SetDefaultConfig
 */
class SetDefaultConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_set_default_config()
    {
        $project = new MockProject();

        $step = new SetDefaultConfig();
        $step->__invoke($project);

        $this->assertEquals(['vendor', 'website'], $project->metadata['exclude']);
    }
}
