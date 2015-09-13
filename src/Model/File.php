<?php

namespace Couscous\Model;

/**
 * Represents a file of the project.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class File
{
    public $relativeFilename;

    /**
     * @var Metadata
     */
    private $metadata;

    public function __construct($relativeFilename)
    {
        $this->relativeFilename = $relativeFilename;
        $this->metadata = new Metadata();
    }

    /**
     * Returns the basename of the file.
     *
     * @return string
     */
    public function getBasename()
    {
        return basename($this->relativeFilename);
    }

    /**
     * Returns the directory of the file.
     *
     * @return string
     */
    public function getDirectory()
    {
        $directory = dirname($this->relativeFilename);
        $directory = ($directory === '.') ? '' : $directory.'/';

        return $directory;
    }

    /**
     * Returns the content of the file.
     *
     * @return string
     */
    abstract public function getContent();

    /**
     * Returns an indexed array of metadata.
     *
     * Array keys are metadata names, array values are metadata values.
     *
     * @return Metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
