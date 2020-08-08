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
     * Run a command.
     *
     * @param string $command The command to be executed.
     *
     * @throws CommandException When the command exit code is not zero.
     *
     * @return string Output of the command.
     */
    public function run($command)
    {
        if ($this->isWindows()) {
            exec($command, $output, $returnValue);
        } else {
            exec($command . ' 2>&1', $output, $returnValue);
        }

        $output = implode(PHP_EOL, $output);

        if ($returnValue !== 0) {
            throw new CommandException($output);
        }

        return $output;
    }

    /**
     * Check if a command exists.
     *
     * @param string $command
     *
     * @return bool
     */
    public function commandExists($command)
    {
        $exists = $this->isWindows() ? 'where' : 'command -v';

        return !empty(exec("$exists $command"));
    }

    /**
     * Check if the OS is Windows.
     *
     * @return bool
     */
    private function isWindows()
    {
        return stripos(PHP_OS, 'WIN') === 0;
    }
}
