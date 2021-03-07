<?php
declare(strict_types = 1);

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

    public function __construct(string $sourceDirectory, string $targetDirectory)
    {
        $this->sourceDirectory = $sourceDirectory;
        $this->targetDirectory = $targetDirectory;
        $this->watchlist = new WatchList();
        $this->metadata = new Metadata();
    }

    public function addFile(File $file): void
    {
        $this->files[$file->relativeFilename] = $file;
    }

    public function removeFile(File $file): void
    {
        unset($this->files[$file->relativeFilename]);
    }

    public function replaceFile(File $oldFile, File $newFile): void
    {
        $this->removeFile($oldFile);
        $this->addFile($newFile);
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param class-string<File> $class
     *
     * @return File[] Instances of $class
     */
    public function findFilesByType(string $class): array
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf(
                "The class %s doesn't exist",
                $class
            ));
        }

        return array_filter($this->files, function (File $file) use ($class): bool {
            return $file instanceof $class;
        });
    }

    /**
     * Returns a Finder correctly set up for searching in source files.
     */
    public function sourceFiles(): Finder
    {
        /** @var list<string> */
        $includedDirectories = $this->metadata['include'] ?: [];

        // To be sure that included directories are under the source one
        if (!empty($includedDirectories)) {
            $includedDirectories = array_map(function (string $item): string {
                return $this->sourceDirectory.'/'.$item;
            }, $includedDirectories);
        }

        /** @var list<string> */
        $exclude = $this->metadata['exclude'] ?: [];
        $excludedDirectories = new ExcludeList($exclude);

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
