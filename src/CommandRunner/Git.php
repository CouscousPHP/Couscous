<?php

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

    public function cloneRepository($url, $directory)
    {
        $this->commandRunner->run("git clone $url \"$directory\"");
    }

    public function checkoutOriginBranch($directory, $branch)
    {
        $this->run($directory, "git checkout -b $branch origin/$branch");
    }

    public function createBranch($directory, $branch)
    {
        $this->run($directory, "git checkout -b $branch");
    }

    public function commitAllChanges($directory, $message)
    {
        $this->run($directory, "git add --all . && git commit -m \"$message\"");
    }

    public function push($directory, $branch, $remote = 'origin')
    {
        $this->run($directory, "git push $remote $branch");
    }

    /**
     * @param string $remote Remote name
     *
     * @return string The git URL
     */
    public function getRemoteUrl($remote = 'origin')
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
    public function hasUncommittedChanges($directory)
    {
        $changes = $this->run($directory, "git diff-index --name-only HEAD");
        return !(ctype_space($changes) || $changes = '');
    }

    private function run($directory, $command)
    {
        return $this->commandRunner->run("cd \"$directory\" && $command");
    }
}
