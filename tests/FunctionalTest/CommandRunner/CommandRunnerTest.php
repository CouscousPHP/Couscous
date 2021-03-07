<?php

namespace Couscous\Tests\FunctionalTest\CommandRunner;

use Couscous\CommandRunner\CommandException;
use Couscous\CommandRunner\CommandRunner;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\CommandRunner\CommandRunner
 */
class CommandRunnerTest extends TestCase
{
    /**
     * @var CommandRunner
     */
    private $commandRunner;

    public function setUp(): void
    {
        $this->commandRunner = new CommandRunner();
    }

    /**
     * @test
     */
    public function successful_command_execution_should_return_output()
    {
        $command = 'echo Couscous';
        $expected = 'Couscous';

        $output = $this->commandRunner->run($command);

        $this->assertEquals($expected, $output);
    }

    /**
     * @test
     */
    public function failure_executing_command_throws_runtime_exception()
    {
        $this->expectException(CommandException::class);
        $command = 'command that produces an error';

        $this->commandRunner->run($command);
    }

    /**
     * @test
     */
    public function successful_command_exists()
    {
        $command = 'echo';
        $expected = true;

        $output = $this->commandRunner->commandExists($command);

        $this->assertEquals($expected, $output);
    }

    /**
     * @test
     */
    public function failure_command_exists()
    {
        $command = 'not_existing_command';
        $expected = false;

        $output = $this->commandRunner->commandExists($command);

        $this->assertEquals($expected, $output);
    }
}
