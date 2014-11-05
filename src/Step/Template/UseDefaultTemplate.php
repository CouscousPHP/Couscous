<?php

namespace Couscous\Step\Template;

use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Uses the default template if none is configured or included in the repository.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class UseDefaultTemplate implements StepInterface
{
    const DEFAULT_TEMPLATE_URL = 'https://github.com/CouscousPHP/Template-Default.git';

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
        if ($repository->config->templateUrl !== null) {
            // Use a remote template
            return;
        }
        if (! is_null($repository->config->directory)) {
            // Customized the template directory: we shouldn't silently override that
            return;
        }
        $templateDirectory = $repository->sourceDirectory . '/' . InitTemplate::DEFAULT_TEMPLATE_DIRECTORY;
        if ($this->filesystem->exists($templateDirectory)) {
            // The repository contains a template
            return;
        }

        $this->setDefaultTemplateUrl($repository);
    }

    protected function setDefaultTemplateUrl(Repository $repository)
    {
        $repository->config->templateUrl = self::DEFAULT_TEMPLATE_URL;
    }
}
