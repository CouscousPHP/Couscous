<?php

namespace Couscous\Step\Template;

use Couscous\Model\HtmlFile;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Renders a template using Twig.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ProcessTwigTemplates implements StepInterface
{
    const DEFAULT_TEMPLATE_NAME = 'default.twig';

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if (! $repository->template) {
            return;
        }

        $twig = $this->createTwig($repository->template->directory);

        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $repository->findFilesByType('Couscous\Model\HtmlFile');

        foreach ($htmlFiles as $file) {
            $template = isset($file->customVariables['template'])
                ? $file->customVariables['template'] . '.twig'
                : self::DEFAULT_TEMPLATE_NAME;

            $context = array_merge(
                $repository->template->templateVariables,
                $repository->config->templateVariables,
                $file->customVariables,
                array('content' => $file->content)
            );

            try {
                $file->content = $twig->render($template, $context);
            } catch (\Exception $e) {
                throw new \RuntimeException(sprintf(
                    'There was an error while rendering the file "%s" with the template "%s": %s',
                    $file->relativeFilename,
                    $template,
                    $e->getMessage()
                ), 0, $e);
            }
        }
    }

    private function createTwig($templateDirectory)
    {
        $loader = new Twig_Loader_Filesystem($templateDirectory);

        return new Twig_Environment($loader, array(
            'cache' => false,
            'auto_reload' => true,
        ));
    }
}