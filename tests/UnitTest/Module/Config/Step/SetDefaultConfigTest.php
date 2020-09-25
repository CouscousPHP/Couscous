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
    public function it_should_set_default_config(): void
    {
        $project = new MockProject();

        $step = new SetDefaultConfig();
        $step->__invoke($project);

        self::assertEquals(['vendor', 'website'], $project->metadata['exclude']);
    }
}
