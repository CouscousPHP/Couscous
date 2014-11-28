<?php

namespace Couscous\Step\Assets;

use Bowerphp\Command\InstallCommand;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Execute the scripts that were set in "after" in the configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RunBowerInstall implements StepInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if ($repository->regenerate) {
            return;
        }
        if (! $repository->metadata['template.directory']) {
            return;
        }

        if (! $this->filesystem->exists($repository->metadata['template.directory'] . '/bower.json')) {
            return;
        }

        $output->writeln('Executing <info>bower install</info>');

        $workingDir = getcwd();
        chdir($repository->metadata['template.directory']);

        $command = new InstallCommand();
        $command->run(new ArrayInput(array()), $output);

        chdir($workingDir);
    }
}
