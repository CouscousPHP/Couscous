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
     *
     * @param string $command The command to be executed.
     *
     * @throws CommandException When the command exit code is not zero.
     *
     * @return string Output of the command.
     */
    public function run($command)
    {
        exec($command.' 2>&1', $output, $returnValue);

        $output = implode(PHP_EOL, $output);

        if ($returnValue !== 0) {
            throw new CommandException($output);
        }

        return $output;
    }
}
