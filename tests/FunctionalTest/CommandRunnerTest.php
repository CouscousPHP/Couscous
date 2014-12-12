<?php

namespace Couscous\Tests\FunctionalTest;

use Couscous\CommandRunner\CommandRunner;

/**
 * @covers Couscous\CommandRunner
 */
class CommandRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandRunner
     */
    private $commandRunner;

    public function setUp()
    {
        $this->commandRunner = new CommandRunner();
    }

    /**
     * @test
     */
    public function successful_command_execution_should_return_output()
    {
        $command    = 'echo Couscous';
        $expected   = 'Couscous';

        $output = $this->commandRunner->run($command);

        $this->assertEquals($expected, $output[0]);
    }

    /**
     * @test
     * @expectedException \Couscous\CommandRunner\CommandException
     */
    public function failure_executing_command_throws_runtime_exception()
    {
        $command = 'command that produces an error';

        $this->commandRunner->run($command);
    }
}
