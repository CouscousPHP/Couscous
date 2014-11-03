<?php

namespace Couscous\Model;

/**
 * Represents a file of the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class File
{
    public $relativeFilename;

    public function __construct($relativeFilename)
    {
        $this->relativeFilename = $relativeFilename;
    }

    public abstract function getContent();
}
