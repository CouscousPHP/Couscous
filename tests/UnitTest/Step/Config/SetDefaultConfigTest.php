<?php

namespace Couscous\Tests\UnitTest\Step\Config;

use Couscous\Step\Config\SetDefaultConfig;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Couscous\Step\Config\SetDefaultConfig
 */
class SetDefaultConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_set_default_config()
    {
        $repository = new MockRepository();

        $step = new SetDefaultConfig();
        $step->__invoke($repository, new NullOutput());

        $this->assertEquals(['vendor', 'website'], $repository->metadata['exclude']);
    }
}
