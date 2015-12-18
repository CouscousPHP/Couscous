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
class AddCname implements Step
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
        if (isset($project->metadata['cname'])) {
            $this->filesystem->dumpFile( $project->targetDirectory.'/'.'CNAME', $project->metadata['cname']);
            $this->logger->notice('Writing metadata CNAME');
        }
    }
}
