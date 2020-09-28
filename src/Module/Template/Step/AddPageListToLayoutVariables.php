<?php
declare(strict_types = 1);

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
    public function __invoke(Project $project): void
    {
        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType(HtmlFile::class);

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

    /**
     * @param array<string, array|string> &$array
     * @param list<string> $path
     */
    private function setValue(array &$array, array $path, string $value): void
    {
        if (empty($path)) {
            $array[$value] = $value;

            return;
        }

        $dir = array_shift($path);

        if (!array_key_exists($dir, $array)) {
            $array[$dir] = [];
        }

        /** @psalm-suppress MixedArgumentTypeCoercion Can't find a way to express type recursion of first argument */
        $this->setValue($array[$dir], $path, $value);
    }

    /**
     * @param array<string, array|string> &$array
     */
    private function sortRecursively(array &$array): void
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                /** @psalm-suppress MixedArgumentTypeCoercion Can't find a way to express type recursion */
                $this->sortRecursively($value);
            }
        }
        ksort($array);
    }
}
