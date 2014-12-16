<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Repository;
use Couscous\Step;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Uses the default template if none is configured or included in the repository.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class UseDefaultTemplate implements \Couscous\Step
{
    const DEFAULT_TEMPLATE_URL = 'https://github.com/CouscousPHP/Template-Light.git';

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
        if ($this->useRemoteTemplate($repository)
            || $this->hasCustomTemplateDirectory($repository)
            || $this->hasTemplateDirectory($repository)
        ) {
            return;
        }

        $repository->metadata['template.url'] = self::DEFAULT_TEMPLATE_URL;
    }

    private function useRemoteTemplate(Repository $repository)
    {
        return $repository->metadata['template.url'] !== null;
    }

    private function hasCustomTemplateDirectory(Repository $repository)
    {
        return $repository->metadata['template.directory'] !== null;
    }

    private function hasTemplateDirectory(Repository $repository)
    {
        $templateDirectory = $repository->sourceDirectory . '/' . ValidateTemplateDirectory::DEFAULT_TEMPLATE_DIRECTORY;

        return $this->filesystem->exists($templateDirectory);
    }
}
