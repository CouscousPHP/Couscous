<?php

namespace Couscous\Tests\UnitTest;

use Couscous\Generator;
use Couscous\Model\Repository;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Generator
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_invoke_every_step()
    {
        $filesystem = $this->createFileSystem();
        $repository = new MockRepository();

        $steps = [
            $this->createStep($repository),
            $this->createStep($repository),
            $this->createStep($repository),
        ];

        $generator = new Generator($filesystem, $steps);

        $generator->generate($repository, new NullOutput());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private function createFilesystem()
    {
        return $this->getMock('Symfony\Component\Filesystem\Filesystem');
    }

    private function createStep(Repository $repository)
    {
        $step = $this->getMockForAbstractClass('Couscous\Step');

        $step->expects($this->once())
            ->method('__invoke')
            ->with($repository);

        return $step;
    }
}
