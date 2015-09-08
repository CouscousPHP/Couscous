<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Project;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Step;

/**
 * Add to the layout variables the list of the pages of the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AddPageListToLayoutVariables implements Step
{
    public function __invoke(Project $project)
    {
        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');

        $pageList = [];
        $pageTree = [];

        foreach ($htmlFiles as $file) {
            $pageList[] = $file->relativeFilename;

            $path = dirname($file->relativeFilename);
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

        $project->metadata['pageList'] = $pageList;
        $project->metadata['pageTree'] = $pageTree;
    }

    private function setValue(array &$array, array $path, $value)
    {
        if (empty($path)) {
            $array[$value] = $value;

            return;
        }

        $dir = array_shift($path);

        if (!array_key_exists($dir, $array)) {
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
