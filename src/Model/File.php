<?php

namespace Couscous\Model;

/**
 * Represents a file of the repository.
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

    /**
     * Returns the content of the file.
     *
     * @return string
     */
    public abstract function getContent();

    /**
     * Returns an indexed array of metadata.
     *
     * Array keys are metadata names, array values are metadata values.
     *
     * @return array
     */
    public abstract function getMetadata();
}
