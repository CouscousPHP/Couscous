<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\Repository;
use Couscous\Step;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Writes the generated files to disk.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class WriteFiles implements Step
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
        foreach ($repository->getFiles() as $file) {
            $targetFilename = $repository->targetDirectory . '/' . $file->relativeFilename;

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln("Writing $targetFilename");
            }

            if ($this->filesystem->exists($targetFilename)) {
                $output->writeln(sprintf(
                    "<comment>Skipping '%s' because a file with the same name already exists</comment>",
                    $file->relativeFilename
                ));
                continue;
            }

            $this->filesystem->dumpFile($targetFilename, $file->getContent());
        }
    }
}
