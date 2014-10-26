<?php

namespace Couscous\Model;

use Symfony\Component\Finder\Finder;

/**
 * Repository containing files.
 *
 * Extends stdClass so that properties can be added by processors at will.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Repository extends \stdClass
{
    /**
     * Directory containing the sources files to process.
     * @var string
     */
    public $sourceDirectory;

    /**
     * Directory in which to generate the website.
     * @var string
     */
    public $targetDirectory;

    /**
     * @var Config
     */
    public $config;

    /**
     * @var Template|null
     */
    public $template;

    /**
     * Files that will be written to disk at the end of the generation.
     *
     * @var File[]
     */
    protected $files = array();

    public function __construct($sourceDirectory, $targetDirectory)
    {
        $this->sourceDirectory = $sourceDirectory;
        $this->targetDirectory = $targetDirectory;
    }

    public function addFile(File $file)
    {
        $this->files[$file->relativeFilename] = $file;
    }

    public function removeFile(File $file)
    {
        unset($this->files[$file->relativeFilename]);
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $class
     * @return File[] Instances of $class
     */
    public function findFilesByType($class)
    {
        return array_filter($this->files, function (File $file) use ($class) {
            return $file instanceof $class;
        });
    }

    /**
     * Returns a Finder correctly set up for searching in source files.
     *
     * @return Finder
     */
    public function sourceFiles()
    {
        $excludedDirectories = $this->config ? $this->config->exclude : array();

        $finder = new Finder();
        $finder->files()
            ->in($this->sourceDirectory)
            ->ignoreDotFiles(true)
            ->exclude(array_merge($excludedDirectories, array('.couscous')));

        return $finder;
    }
}
