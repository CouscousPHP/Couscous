<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Uses the default template if none is configured or included in the repository.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class UseDefaultTemplate implements Step
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

    public function __invoke(Project $project)
    {
        if ($this->useRemoteTemplate($project)
            || $this->hasCustomTemplateDirectory($project)
            || $this->hasTemplateDirectory($project)
        ) {
            return;
        }

        $project->metadata['template.url'] = self::DEFAULT_TEMPLATE_URL;
    }

    private function useRemoteTemplate(Project $project)
    {
        return $project->metadata['template.url'] !== null;
    }

    private function hasCustomTemplateDirectory(Project $project)
    {
        return $project->metadata['template.directory'] !== null;
    }

    private function hasTemplateDirectory(Project $project)
    {
        $templateDirectory = $project->sourceDirectory.'/'.ValidateTemplateDirectory::DEFAULT_TEMPLATE_DIRECTORY;

        return $this->filesystem->exists($templateDirectory);
    }
}
