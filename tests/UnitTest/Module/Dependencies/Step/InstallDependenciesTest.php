<?php

namespace Couscous\Tests\UnitTest\Module\Dependencies\Step;

use Couscous\CommandRunner\CommandRunner;
use Couscous\Module\Dependencies\Step\InstallDependencies;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Module\Dependencies\Step\InstallDependencies
 */
class InstallDependenciesTest extends TestCase
{
    /**
     * @var MockObject|Filesystem
     */
    private $filesystem;

    /**
     * @var MockObject|CommandRunner
     */
    private $commandRunner;

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->createMock(Filesystem::class);
        $this->commandRunner = $this->createMock(CommandRunner::class);
    }

    public function testInstallDependenciesYarn()
    {
        $this->filesystem->method('exists')->will($this->returnValueMap([
            ['/hello/world/package.json', true],
            ['/hello/world/bower.json', false]
        ]));
        $this->commandRunner->method('commandExists')->will($this->returnValueMap([
            ['yarn', true],
            ['npm', false],
            ['bower', false]
        ]));

        $this->assertCommandRun('cd "/hello/world" && yarn install');
    }

    public function testInstallDependenciesNpm()
    {
        $this->filesystem->method('exists')->will($this->returnValueMap([
            ['/hello/world/package.json', true],
            ['/hello/world/bower.json', false]
        ]));
        $this->commandRunner->method('commandExists')->will($this->returnValueMap([
            ['yarn', false],
            ['npm', true],
            ['bower', false]
        ]));

        $this->assertCommandRun('cd "/hello/world" && npm install');
    }

    public function testInstallDependenciesBower()
    {
        $this->filesystem->method('exists')->will($this->returnValueMap([
            ['/hello/world/package.json', false],
            ['/hello/world/bower.json', true]
        ]));
        $this->commandRunner->method('commandExists')->will($this->returnValueMap([
            ['yarn', false],
            ['npm', false],
            ['bower', true]
        ]));

        $this->assertCommandRun('cd "/hello/world" && bower install');
    }

    private function assertCommandRun($expectedCommandRun)
    {
        $step = new InstallDependencies($this->filesystem, $this->commandRunner, new NullLogger());

        $project = new MockProject();
        $project->metadata['template.directory'] = '/hello/world';

        $this->commandRunner->expects($this->once())->method('run')->with($expectedCommandRun);

        $step->__invoke($project);
    }
}
