<?php

namespace Couscous\Tests\UnitTest;

use Couscous\Generator;
use Couscous\Model\Project;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Couscous\Step;

#[CoversClass(Generator::class)]
class GeneratorTest extends TestCase
{
    #[Test]
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
     * @return MockObject|Filesystem
     */
    private function createFilesystem()
    {
        return $this->createMock(Filesystem::class);
    }

    private function createStep(Project $project)
    {
        $step = $this->getMockBuilder(Step::class)->onlyMethods(['__invoke'])->getMock();

        $step->expects($this->once())
            ->method('__invoke')
            ->with($project);

        return $step;
    }
}
