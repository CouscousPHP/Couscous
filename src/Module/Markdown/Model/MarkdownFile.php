<?php

namespace Couscous\Module\Markdown\Model;

use Couscous\Model\File;
use Couscous\Model\Metadata;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MarkdownFile extends File
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var Metadata
     */
    private $metadata;

    public function __construct($relativeFilename, $content)
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
        $this->metadata = new Metadata();
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }
}
