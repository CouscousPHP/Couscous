<?php

namespace Couscous\Step\Template;

use Couscous\Model\Repository;
use Couscous\Model\Template;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initializes the website template.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InitTemplate implements StepInterface
{
    const TEMPLATE_DIRECTORY = 'website';
    const PUBLIC_DIRECTORY = 'public';

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $templateDirectory = $repository->sourceDirectory . '/' . self::TEMPLATE_DIRECTORY;
        $publicDirectory = $templateDirectory . '/' . self::PUBLIC_DIRECTORY;

        if (! file_exists($templateDirectory)) {
            throw new \RuntimeException("The template directory doesn't exist: $templateDirectory");
        }

        $repository->template = new Template($templateDirectory, $publicDirectory);
    }
}
