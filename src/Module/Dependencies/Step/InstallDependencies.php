<?php

namespace Couscous\Module\Dependencies\Step;

use Couscous\CommandRunner\CommandRunner;
use Couscous\Model\Project;
use Couscous\Step;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Install dependencies using yarn, npm or bower.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InstallDependencies implements Step
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Filesystem $filesystem,
        CommandRunner $commandRunner,
        LoggerInterface $logger
    ) {
        $this->filesystem = $filesystem;
        $this->commandRunner = $commandRunner;
        $this->logger = $logger;
    }

    public function __invoke(Project $project)
    {
        $canUseYarn = $this->canUseYarn($project);
        $canUseNpm = $this->canUseNpm($project);
        $canUseBower = $this->canUseBower($project);

        if ($project->regenerate || (!$canUseYarn && !$canUseNpm && !$canUseBower)) {
            return;
        }

        $command = 'bower';
        if ($canUseNpm) {
            $command = 'npm';
        }
        // Use yarn preferably
        if ($canUseYarn) {
            $command = 'yarn';
        }

        $this->logger->notice("Executing \"$command install\"");

        $result = $this->commandRunner->run(sprintf(
            "cd \"%s\" && $command install",
            $project->metadata['template.directory']
        ));

        if ($result) {
            $this->logger->info($result);
        }
    }

    /**
     * @return bool
     */
    private function canUseYarn(Project $project)
    {
        return $this->hasPackageJson($project) && $this->commandRunner->commandExists('yarn');
    }

    /**
     * @return bool
     */
    private function canUseNpm(Project $project)
    {
        return $this->hasPackageJson($project) && $this->commandRunner->commandExists('npm');
    }

    /**
     * @return bool
     */
    private function canUseBower(Project $project)
    {
        return $this->hasBowerJson($project) && $this->commandRunner->commandExists('bower');
    }

    /**
     * @return bool
     */
    private function hasPackageJson(Project $project)
    {
        if (!$project->metadata['template.directory']) {
            return false;
        }

        $filename = $project->metadata['template.directory'].'/package.json';

        return $this->filesystem->exists($filename);
    }

    /**
     * @return bool
     */
    private function hasBowerJson(Project $project)
    {
        if (!$project->metadata['template.directory']) {
            return false;
        }

        $filename = $project->metadata['template.directory'].'/bower.json';

        return $this->filesystem->exists($filename);
    }
}
