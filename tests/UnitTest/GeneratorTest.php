<?php

namespace Couscous\Tests\UnitTest;

use Couscous\Generator;
use Couscous\Model\Project;
use Couscous\Tests\UnitTest\Mock\MockProject;
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
        $project = new MockProject();

        $steps = [
            $this->createStep($project),
            $this->createStep($project),
            $this->createStep($project),
        ];

        $generator = new Generator($filesystem, $steps);

        $generator->generate($project, new NullOutput());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private function createFilesystem()
    {
        return $this->getMock('Symfony\Component\Filesystem\Filesystem');
    }

    private function createStep(Project $project)
    {
        $step = $this->getMockForAbstractClass('Couscous\Step');

        $step->expects($this->once())
            ->method('__invoke')
            ->with($project);

        return $step;
    }
}
