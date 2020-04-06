<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Project;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Step;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Twig_Environment;
use Twig_Loader_Array;

/**
 * Renders file layouts using Twig.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ProcessTwigLayouts implements Step
{
    const DEFAULT_LAYOUT_NAME = 'default.twig';

    public function __invoke(Project $project)
    {
        if (!$project->metadata['template.directory']) {
            return;
        }

        $twig = $this->createTwig($project->metadata['template.directory']);

        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');

        foreach ($htmlFiles as $file) {
            $fileMetadata = $file->getMetadata();
            $layout = isset($fileMetadata['layout'])
                ? $fileMetadata['layout'].'.twig'
                : self::DEFAULT_LAYOUT_NAME;

            $context = array_merge(
                $project->metadata->toArray(),
                $fileMetadata->toArray(),
                ['content' => $file->content]
            );

            try {
                $file->content = $twig->render($layout, $context);
            } catch (\Exception $e) {
                throw new \RuntimeException(sprintf(
                    'There was an error while rendering the file "%s" with the layout "%s": %s',
                    $file->relativeFilename,
                    $layout,
                    $e->getMessage()
                ), 0, $e);
            }
        }
    }

    private function createTwig($templateDirectory)
    {
        $loader = $this->createLoader($templateDirectory);

        $twig = new Twig_Environment($loader, [
            'cache'       => false,
            'auto_reload' => true,
        ]);

        if (file_exists($templateDirectory.'/twig.php')) {
            $customLoader = require $templateDirectory.'/twig.php';
            $customLoader($twig);
        }

        return $twig;
    }

    /**
     * We have to use a Twig_Loader_Array because of #12.
     *
     * @link https://github.com/CouscousPHP/Couscous/issues/12
     *
     * @param string $templateDirectory
     *
     * @return Twig_Loader_Array
     */
    private function createLoader($templateDirectory)
    {
        $finder = new Finder();
        $finder->files()
            ->in($templateDirectory)
            ->name('*.twig');

        $layouts = [];
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $name = $file->getFilename();
            $layouts[$name] = $file->getContents();
        }

        return new Twig_Loader_Array($layouts);
    }
}
