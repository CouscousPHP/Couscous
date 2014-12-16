<?php

namespace Couscous\CommandRunner;

/**
 * Run CLI commands.
 *
 * @author Carlos Lombarte <lombartec@gmail.com>
 */
class CommandRunner
{
    /**
     * Runs a command.
     *
     * @throws CommandException When the command exit code is not zero.
     *
     * @param string $command The command to be executed.
     *
     * @return mixed
     */
    public function run($command)
    {
        exec($command . ' 2>&1', $commandOutput, $returnValue);

        if ($returnValue !== 0) {
            throw new CommandException(implode(PHP_EOL, $commandOutput));
        }

        return $commandOutput;
    }
}
