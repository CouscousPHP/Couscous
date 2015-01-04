<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Repository;
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

    public function __invoke(Repository $repository)
    {
        $directory = $repository->metadata['template.directory'];

        if ($directory === null) {
            $directory = $repository->sourceDirectory . '/' . self::DEFAULT_TEMPLATE_DIRECTORY;
        }

        if (! $this->filesystem->isAbsolutePath($directory)) {
            $directory = $repository->sourceDirectory . '/' . $directory;
        }

        $this->assertDirectoryExist($directory);

        $repository->watchlist->watchDirectory($directory);

        $repository->metadata['template.directory'] = $directory;
    }

    private function assertDirectoryExist($directory)
    {
        if (! $this->filesystem->exists($directory)) {
            throw new \RuntimeException(sprintf(
                "The template directory '%s' doesn't exist",
                $directory
            ));
        }
    }
}
