<?php

namespace Couscous\Model;

use Couscous\Model\WatchList\WatchList;
use Symfony\Component\Finder\Finder;

/**
 * Project containing files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Project
{
    /**
     * Directory containing the sources files to process.
     *
     * @var string
     */
    public $sourceDirectory;

    /**
     * Directory in which to generate the website.
     *
     * @var string
     */
    public $targetDirectory;

    /**
     * @var Metadata
     */
    public $metadata;

    /**
     * If true then we are in "preview" mode and we are regenerating the website.
     *
     * Use this to prevent doing anything costly twice so that the regeneration is faster,
     * e.g. downloading files…
     *
     * @var bool
     */
    public $regenerate = false;

    /**
     * @var WatchList
     */
    public $watchlist;

    /**
     * Files that will be written to disk at the end of the generation.
     *
     * @var File[]
     */
    protected $files = [];

    public function __construct($sourceDirectory, $targetDirectory)
    {
        $this->sourceDirectory = $sourceDirectory;
        $this->targetDirectory = $targetDirectory;
        $this->watchlist = new WatchList();
        $this->metadata = new Metadata();
    }

    public function addFile(File $file)
    {
        $this->files[$file->relativeFilename] = $file;
    }

    public function removeFile(File $file)
    {
        unset($this->files[$file->relativeFilename]);
    }

    public function replaceFile(File $oldFile, File $newFile)
    {
        $this->removeFile($oldFile);
        $this->addFile($newFile);
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
     *
     * @return File[] Instances of $class
     */
    public function findFilesByType($class)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf(
                "The class %s doesn't exist",
                $class
            ));
        }

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
        $includedDirectories = $this->metadata['include'] ? $this->metadata['include'] : [];

        // To be sure that included directories are under the source one
        if (!empty($includedDirectories)) {
            array_walk($includedDirectories, function (&$item) {
                $item = $this->sourceDirectory.'/'.$item;
            });
        }

        $excludedDirectories = new ExcludeList($this->metadata['exclude'] ? $this->metadata['exclude'] : []);

        if (is_file($this->sourceDirectory.'/.gitignore')) {
            $excludedDirectories->addEntries(file($this->sourceDirectory.'/.gitignore'));
        }

        $finder = new Finder();
        $finder->files()
            ->followLinks()
            ->in(!empty($includedDirectories) ? $includedDirectories : $this->sourceDirectory)
            ->ignoreDotFiles(true);

        $excludedDirectories
            ->addEntry('.couscous')
            ->excludeFromFinder($finder);

        return $finder;
    }
}
