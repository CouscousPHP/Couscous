<?php
declare(strict_types = 1);

namespace Couscous\Model;

/**
 * Generic implementation that reads a file lazily.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LazyFile extends File
{
    /**
     * @var string
     */
    private $fullFilename;

    public function __construct(string $fullFilename, string $relativeFilename)
    {
        $this->fullFilename = $fullFilename;

        parent::__construct($relativeFilename);
    }

    public function getContent(): string
    {
        return file_get_contents($this->fullFilename);
    }
}
