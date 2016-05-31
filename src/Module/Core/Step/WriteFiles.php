<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Psr\Log\LoggerInterface;
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

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Filesystem $filesystem, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function __invoke(Project $project)
    {
        foreach ($project->getFiles() as $file) {
            $targetFilename = $project->targetDirectory.'/'.$file->relativeFilename;

            if ($this->filesystem->exists($targetFilename)) {
                $this->logger->info(
                    "Skipping '{file}' because a file with the same name already exists",
                    ['file' => $file->relativeFilename]
                );
                continue;
            }

            $this->logger->debug('Writing {file}', ['file' => $targetFilename]);

            $this->filesystem->dumpFile($targetFilename, $file->getContent());
        }
    }
}
