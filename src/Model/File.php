<?php

namespace Couscous\Model;

/**
 * Represents a file of the project.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class File
{
    /**
     * @var string
     */
    public $relativeFilename;

    /**
     * @var Metadata
     */
    private $metadata;

    public function __construct(string $relativeFilename)
    {
        $this->relativeFilename = $relativeFilename;
        $this->metadata = new Metadata();
    }

    /**
     * Returns the basename of the file.
     *
     * @return string
     */
    public function getBasename(): string
    {
        return basename($this->relativeFilename);
    }

    /**
     * Returns the directory of the file.
     *
     * @return string
     */
    public function getDirectory(): string
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
    abstract public function getContent(): string;

    /**
     * Returns an indexed array of metadata.
     *
     * Array keys are metadata names, array values are metadata values.
     *
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }
}
