<?php

namespace Couscous\Step\Template;

use Couscous\Model\HtmlFile;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add to the template variables the list of the pages of the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AddPageListToTemplateVariables implements StepInterface
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if (! $repository->template) {
            return;
        }

        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $repository->findFilesByType('Couscous\Model\HtmlFile');

        $pageList = array();
        $pageTree = array();

        foreach ($htmlFiles as $file) {
            $pageList[] = $file->relativeFilename;

            $path = dirname($file->relativeFilename);
            $filename = basename($file->relativeFilename);

            if ($path === '.') {
                $path = array();
            } else {
                $path = explode(DIRECTORY_SEPARATOR, $path);
            }

            $this->setValue($pageTree, $path, $filename);
        }

        $repository->template->templateVariables['pageList'] = $pageList;
        $repository->template->templateVariables['pageTree'] = $pageTree;
    }

    private function setValue(array &$array, array $path, $value)
    {
        if (empty($path)) {
            $array[] = $value;
            return;
        }

        $dir = array_shift($path);

        if (! array_key_exists($dir, $array)) {
            $array[$dir] = array();
        }

        $this->setValue($array[$dir], $path, $value);
    }
}
