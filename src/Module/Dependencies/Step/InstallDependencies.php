<?php
declare(strict_types = 1);

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

    public function __invoke(Project $project): void
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

        /** @var string */
        $templateDirectory = $project->metadata['template.directory'];
        $result = $this->commandRunner->run(sprintf(
            "cd \"%s\" && $command install",
            $templateDirectory
        ));

        if ($result) {
            $this->logger->info($result);
        }
    }

    private function canUseYarn(Project $project): bool
    {
        return $this->hasPackageJson($project) && $this->commandRunner->commandExists('yarn');
    }

    private function canUseNpm(Project $project): bool
    {
        return $this->hasPackageJson($project) && $this->commandRunner->commandExists('npm');
    }

    private function canUseBower(Project $project): bool
    {
        return $this->hasBowerJson($project) && $this->commandRunner->commandExists('bower');
    }

    private function hasPackageJson(Project $project): bool
    {
        if (!$project->metadata['template.directory']) {
            return false;
        }

        $filename = ((string) $project->metadata['template.directory']).'/package.json';

        return $this->filesystem->exists($filename);
    }

    private function hasBowerJson(Project $project): bool
    {
        if (!$project->metadata['template.directory']) {
            return false;
        }

        $filename = ((string) $project->metadata['template.directory']).'/bower.json';

        return $this->filesystem->exists($filename);
    }
}
