<?php

namespace Couscous\Tests\UnitTest;

use Couscous\Generator;
use Couscous\Model\Repository;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
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
        $output = new NullOutput();

        $steps = [
            $this->createStep($repository, $output),
            $this->createStep($repository, $output),
            $this->createStep($repository, $output),
        ];

        $generator = new Generator($filesystem, $steps);

        $generator->generate($repository, $output);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private function createFilesystem()
    {
        return $this->getMock('Symfony\Component\Filesystem\Filesystem');
    }

    private function createStep(Repository $repository, OutputInterface $output)
    {
        $step = $this->getMockForAbstractClass('Couscous\Step');

        $step->expects($this->once())
            ->method('__invoke')
            ->with($repository, $output);

        return $step;
    }
}
