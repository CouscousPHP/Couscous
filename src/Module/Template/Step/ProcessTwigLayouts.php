<?php
declare(strict_types = 1);

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
    private const DEFAULT_LAYOUT_NAME = 'default.twig';

    public function __invoke(Project $project): void
    {
        if (!$project->metadata['template.directory']) {
            return;
        }

        $twig = $this->createTwig((string) $project->metadata['template.directory']);

        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType(HtmlFile::class);

        /** @var HtmlFile */
        foreach ($htmlFiles as $file) {
            $fileMetadata = $file->getMetadata();
            $layout = isset($fileMetadata['layout'])
                ? ((string) $fileMetadata['layout']).'.twig'
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

    private function createTwig(string $templateDirectory): Twig_Environment
    {
        $loader = $this->createLoader($templateDirectory);

        $twig = new Twig_Environment($loader, [
            'cache'       => false,
            'auto_reload' => true,
        ]);

        if (file_exists($templateDirectory.'/twig.php')) {
            /**
             * @psalm-suppress UnresolvableInclude
             * @var callable(Twig_Environment): void
             */
            $customLoader = require $templateDirectory.'/twig.php';
            $customLoader($twig);
        }

        return $twig;
    }

    /**
     * We have to use a Twig_Loader_Array because of #12.
     *
     * @link https://github.com/CouscousPHP/Couscous/issues/12
     */
    private function createLoader(string $templateDirectory): Twig_Loader_Array
    {
        $finder = new Finder();
        $finder->files()
            ->in($templateDirectory)
            ->name('*.twig');

        $layouts = [];
        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $name = $file->getFilename();
            $layouts[$name] = $file->getContents();
        }

        return new Twig_Loader_Array($layouts);
    }
}
