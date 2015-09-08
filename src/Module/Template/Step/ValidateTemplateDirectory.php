<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Initializes the template directory.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ValidateTemplateDirectory implements Step
{
    const DEFAULT_TEMPLATE_DIRECTORY = 'website';

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function __invoke(Project $project)
    {
        $directory = $project->metadata['template.directory'];

        if ($directory === null) {
            $directory = $project->sourceDirectory.'/'.self::DEFAULT_TEMPLATE_DIRECTORY;
        }

        if (!$this->filesystem->isAbsolutePath($directory)) {
            $directory = $project->sourceDirectory.'/'.$directory;
        }

        $this->assertDirectoryExist($directory);

        $project->watchlist->watchDirectory($directory);

        $project->metadata['template.directory'] = $directory;
    }

    private function assertDirectoryExist($directory)
    {
        if (!$this->filesystem->exists($directory)) {
            throw new \RuntimeException(sprintf(
                "The template directory '%s' doesn't exist",
                $directory
            ));
        }
    }
}
