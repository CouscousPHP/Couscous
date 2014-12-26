<?php

namespace Couscous\Module\Bower\Step;

use Couscous\CommandRunner\CommandRunner;
use Couscous\Model\Repository;
use Couscous\Step;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Run Bower install.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RunBowerInstall implements Step
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var CommandRunner
     */
    private $commandRunner;

    public function __construct(Filesystem $filesystem, CommandRunner $commandRunner)
    {
        $this->filesystem = $filesystem;
        $this->commandRunner = $commandRunner;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if ($repository->regenerate || !$this->hasBowerJson($repository)) {
            return;
        }

        $output->writeln('Executing <info>bower install</info>');

        $result = $this->commandRunner->run(sprintf(
            'cd "%s" && bower install',
            $repository->metadata['template.directory']
        ));

        if ($result) {
            $output->writeln($result);
        }
    }

    private function hasBowerJson(Repository $repository)
    {
        if (! $repository->metadata['template.directory']) {
            return false;
        }

        $filename = $repository->metadata['template.directory'] . '/bower.json';

        return $this->filesystem->exists($filename);
    }
}
