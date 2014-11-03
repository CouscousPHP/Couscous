<?php

namespace Couscous\Model\WatchList;

/**
 * Watch a file or a series of files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface WatchInterface
{
    /**
     * Returns the list of files that have changed.
     *
     * @return string[]
     */
    public function getChangedFiles();
}
