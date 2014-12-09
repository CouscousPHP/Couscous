<?php

namespace Couscous\Tests\FunctionalTest;

use Couscous\CommandException;
use Couscous\CommandRunner;

/**
 * @covers Couscous\CommandRunner
 */
class CommandRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandRunner
     */
    private $command_runner;

    public function setUp()
    {
        $this->command_runner = new CommandRunner();
    }

    /**
     * @test
     */
    public function successful_command_execution_should_return_output()
    {
        $command    = 'echo Couscous';
        $expected   = 'Couscous';

        $output = $this->command_runner->run($command);

        $this->assertEquals($expected, $output[0]);
    }

    /**
     * @test
     * @expectedException \Couscous\CommandException
     */
    public function failure_executing_command_throws_runtime_exception()
    {
        $command = 'command that produces an error';

        $this->command_runner->run($command);
    }
}
