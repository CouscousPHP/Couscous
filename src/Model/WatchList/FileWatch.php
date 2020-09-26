<?php
declare(strict_types = 1);

namespace Couscous\Model\WatchList;

/**
 * Watch a file.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FileWatch implements WatchInterface
{
    /**
     * @var string
     */
    private $filename;
    /**
     * @var int
     */
    private $time;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->time = time();
    }

    public function getChangedFiles(): array
    {
        if (!file_exists($this->filename)) {
            return [];
        }

        if (filemtime($this->filename) > $this->time) {
            return [$this->filename];
        }

        return [];
    }
}
