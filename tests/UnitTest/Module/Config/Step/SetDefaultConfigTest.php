<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\SetDefaultConfig;
use Couscous\Tests\UnitTest\Mock\MockRepository;

/**
 * @covers \Couscous\Module\Config\Step\SetDefaultConfig
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
        $step->__invoke($repository);

        $this->assertEquals(['vendor', 'website'], $repository->metadata['exclude']);
    }
}
