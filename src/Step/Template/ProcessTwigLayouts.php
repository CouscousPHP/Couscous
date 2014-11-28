<?php

namespace Couscous\Step\Template;

use Couscous\Model\HtmlFile;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Twig_Environment;
use Twig_Loader_Array;

/**
 * Renders file layouts using Twig.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ProcessTwigLayouts implements StepInterface
{
    const DEFAULT_LAYOUT_NAME = 'default.twig';

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if (! $repository->metadata['template.directory']) {
            return;
        }

        $twig = $this->createTwig($repository->metadata['template.directory']);

        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $repository->findFilesByType('Couscous\Model\HtmlFile');

        foreach ($htmlFiles as $file) {
            $fileMetadata = $file->getMetadata();
            $layout = isset($fileMetadata['layout'])
                ? $fileMetadata['layout'] . '.twig'
                : self::DEFAULT_LAYOUT_NAME;

            $context = array_merge(
                $repository->metadata->toArray(),
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

        return new Twig_Environment($loader, array(
            'cache' => false,
            'auto_reload' => true,
        ));
    }

    /**
     * We have to use a Twig_Loader_Array because of #12
     *
     * @link https://github.com/CouscousPHP/Couscous/issues/12
     *
     * @param string $templateDirectory
     * @return Twig_Loader_Array
     */
    private function createLoader($templateDirectory)
    {
        $finder = new Finder();
        $finder->files()
            ->in($templateDirectory)
            ->name('*.twig');

        $layouts = array();
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $name = $file->getFilename();
            $layouts[$name] = $file->getContents();
        }

        return new Twig_Loader_Array($layouts);
    }
}
