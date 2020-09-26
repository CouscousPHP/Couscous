<?php
declare(strict_types = 1);

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
     */
    public function getBasename(): string
    {
        return basename($this->relativeFilename);
    }

    /**
     * Returns the directory of the file.
     */
    public function getDirectory(): string
    {
        $directory = dirname($this->relativeFilename);
        $directory = ($directory === '.') ? '' : $directory.'/';

        return $directory;
    }

    /**
     * Returns the content of the file.
     */
    abstract public function getContent(): string;

    /**
     * Returns an indexed array of metadata.
     *
     * Array keys are metadata names, array values are metadata values.
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }
}
