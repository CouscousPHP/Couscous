<?php

namespace Couscous\Module\Template\Step;

use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Model\Repository;
use Couscous\Step;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add to the layout variables the list of the pages of the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AddPageListToLayoutVariables implements \Couscous\Step
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $repository->findFilesByType('Couscous\Module\Template\Model\HtmlFile');

        $pageList = [];
        $pageTree = [];

        foreach ($htmlFiles as $file) {
            $pageList[] = $file->relativeFilename;

            $path     = dirname($file->relativeFilename);
            $filename = basename($file->relativeFilename);

            if ($path === '.') {
                $path = [];
            } else {
                $path = explode(DIRECTORY_SEPARATOR, $path);
            }

            $this->setValue($pageTree, $path, $filename);
        }

        // Sort
        natsort($pageList);
        $this->sortRecursively($pageTree);

        $repository->metadata['pageList'] = $pageList;
        $repository->metadata['pageTree'] = $pageTree;
    }

    private function setValue(array &$array, array $path, $value)
    {
        if (empty($path)) {
            $array[$value] = $value;
            return;
        }

        $dir = array_shift($path);

        if (! array_key_exists($dir, $array)) {
            $array[$dir] = [];
        }

        $this->setValue($array[$dir], $path, $value);
    }

    private function sortRecursively(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->sortRecursively($value);
            }
        }
        ksort($array);
    }
}
