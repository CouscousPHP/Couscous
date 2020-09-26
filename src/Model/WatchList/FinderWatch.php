<?php
declare(strict_types = 1);

namespace Couscous\Model\WatchList;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Watch several files with a Symfony Finder object.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FinderWatch implements WatchInterface
{
    /**
     * @var Finder
     */
    private $finder;
    /**
     * @var string
     */
    private $date;

    public function __construct(Finder $finder)
    {
        $this->finder = clone $finder;
        $this->date = date('Y-m-d H:i:s');
    }

    public function getChangedFiles(): array
    {
        $finder = clone $this->finder;
        $finder->date('after '.$this->date);

        return array_map(function (SplFileInfo $file): string {
            return $file->getPathname();
        }, iterator_to_array($finder));
    }
}
