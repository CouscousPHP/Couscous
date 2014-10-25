<?php

namespace Couscous\Model;

/**
 * Generic implementation that reads a file lazily.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LazyFile extends File
{
    public function getContent()
    {
        return file_get_contents($this->relativeFilename);
    }
}
