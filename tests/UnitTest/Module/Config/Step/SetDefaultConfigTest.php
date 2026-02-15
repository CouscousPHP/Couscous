<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\SetDefaultConfig;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SetDefaultConfig::class)]
class SetDefaultConfigTest extends TestCase
{
    #[Test]
    public function it_should_set_default_config()
    {
        $project = new MockProject();

        $step = new SetDefaultConfig();
        $step->__invoke($project);

        $this->assertEquals(['vendor', 'website'], $project->metadata['exclude']);
    }
}
