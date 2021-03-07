<?php
declare(strict_types = 1);

namespace Couscous\CommandRunner;

/**
 * Wrapper around git commands.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Git
{
    /**
     * @var CommandRunner
     */
    private $commandRunner;

    public function __construct(CommandRunner $commandRunner)
    {
        $this->commandRunner = $commandRunner;
    }

    public function cloneRepository(string $url, string $directory): void
    {
        $this->commandRunner->run("git clone $url \"$directory\"");
    }

    public function checkoutOriginBranch(string $directory, string $branch): void
    {
        $this->run($directory, "git checkout -b $branch origin/$branch");
    }

    public function createBranch(string $directory, string $branch): void
    {
        $this->run($directory, "git checkout -b $branch");
    }

    public function commitAllChanges(string $directory, string $message): void
    {
        $this->run($directory, "git add --all . && git commit -m \"$message\"");
    }

    public function push(string $directory, string $branch, string $remote = 'origin'): void
    {
        $this->run($directory, "git push $remote $branch");
    }

    /**
     * @param string $remote Remote name
     *
     * @return string The git URL
     * @throws CommandException
     */
    public function getRemoteUrl(string $remote = 'origin'): string
    {
        $command = "git config --get remote.$remote.url";

        return trim($this->commandRunner->run($command));
    }

    /**
     * Check if the git repository in the provided directory has any uncommitted changes
     *
     * @param string $directory A directory containing a git repository
     * @return bool True if there are changes, false otherwise
     */
    public function hasUncommittedChanges(string $directory): bool
    {
        $changes = $this->run($directory, "git diff-index --name-only HEAD");
        return !(ctype_space($changes) || $changes = '');
    }

    private function run(string $directory, string $command): string
    {
        return $this->commandRunner->run("cd \"$directory\" && $command");
    }
}
