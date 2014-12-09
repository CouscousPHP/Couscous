<?php

namespace Couscous\Tests\UnitTest\Step\Template;

use Couscous\CommandRunner;
use Couscous\Step\Template\InitTemplate;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Step\Template\InitTemplate
 */
class InitTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InitTemplate
     */
    private $step;

    public function setUp()
    {
        $this->step = new InitTemplate($this->createFilesystem(), $this->createCommandRunner());
    }

    public function testDefaultDirectory()
    {
        $repository = new MockRepository();
        $this->step->__invoke($repository, new NullOutput());

        $this->assertEquals('/website', $repository->metadata['template.directory']);
    }

    public function testCustomDirectory()
    {
        $repository = new MockRepository();
        $repository->metadata['template.directory'] = 'foo';
        $this->step->__invoke($repository, new NullOutput());

        $this->assertEquals('/foo', $repository->metadata['template.directory']);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private function createFilesystem()
    {
        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $filesystem->expects($this->any())
            ->method('exists')
            ->willReturn(true);
        return $filesystem;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CommandRunner
     */
    private function createCommandRunner()
    {
        $command_runner = $this->getMock('Couscous\CommandRunner');
        $command_runner->expects($this->any())
            ->method('run')
            ->willReturn(true);
        return $command_runner;
    }
}
