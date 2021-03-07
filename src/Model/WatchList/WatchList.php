<?php
declare(strict_types = 1);

namespace Couscous\Model\WatchList;

use Symfony\Component\Finder\Finder;

/**
 * List of files or directories to watch for continuous regeneration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class WatchList
{
    /**
     * @var list<WatchInterface>
     */
    private $watches = [];

    public function watchFile(string $filename): void
    {
        $this->watches[] = new FileWatch($filename);
    }

    public function watchFiles(Finder $finder): void
    {
        $this->watches[] = new FinderWatch($finder);
    }

    public function watchDirectory(string $directory): void
    {
        $finder = new Finder();
        $finder->files()
            ->in($directory);

        $this->watches[] = new FinderWatch($finder);
    }

    public function getChangedFiles(): array
    {
        $files = array_map(function (WatchInterface $watch): array {
            return $watch->getChangedFiles();
        }, $this->watches);

        /** @var list<string> */
        $files = array_merge(...$files);

        return array_unique($files);
    }
}
