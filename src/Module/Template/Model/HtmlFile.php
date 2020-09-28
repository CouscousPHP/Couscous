<?php
declare(strict_types = 1);

namespace Couscous\Module\Template\Model;

use Couscous\Model\File;
use Couscous\Model\Metadata;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class HtmlFile extends File
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var ?File
     */
    private $wrappedFile;

    public function __construct(string $relativeFilename, string $content, File $wrappedFile = null)
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
        $this->wrappedFile = $wrappedFile;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMetadata(): Metadata
    {
        return $this->wrappedFile ? $this->wrappedFile->getMetadata() : parent::getMetadata();
    }
}
