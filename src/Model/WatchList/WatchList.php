<?php

namespace Couscous\Model\WatchList;

use Symfony\Component\Finder\Finder;

/**
 * List of files or directories to watch for continuous regeneration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class WatchList
{
    private $watches;

    public function watchFile($filename)
    {
        $this->watches[] = new FileWatch($filename);
    }

    public function watchFiles(Finder $finder)
    {
        $this->watches[] = new FinderWatch($finder);
    }

    public function watchDirectory($directory)
    {
        $finder = new Finder();
        $finder->files()
            ->in($directory);

        $this->watches[] = new FinderWatch($finder);
    }

    public function getChangedFiles()
    {
        $files = array_map(function (WatchInterface $watch) {
            return $watch->getChangedFiles();
        }, $this->watches);

        $files = call_user_func_array('array_merge', $files);

        return array_unique($files);
    }
}
