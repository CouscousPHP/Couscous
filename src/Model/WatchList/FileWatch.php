<?php

namespace Couscous\Model\WatchList;

/**
 * Watch a file.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FileWatch implements WatchInterface
{
    private $filename;
    private $time;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->time = time();
    }

    public function getChangedFiles()
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
