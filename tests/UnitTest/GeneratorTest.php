<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest;

use Couscous\Generator;
use Couscous\Model\Project;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Couscous\Step;

/**
 * @covers \Couscous\Generator
 */
class GeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_invoke_every_step(): void
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
     * @return MockObject&Filesystem
     */
    private function createFilesystem(): MockObject
    {
        return $this->createMock(Filesystem::class);
    }

    /**
     * @return MockObject&Step
     */
    private function createStep(Project $project): MockObject
    {
        $step = $this->getMockForAbstractClass(Step::class);

        $step->expects(self::once())
            ->method('__invoke')
            ->with($project);

        return $step;
    }
}
