<?php

namespace Couscous\Model;

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
     * @var array
     */
    private $metadata;

    public function __construct($relativeFilename, $content, array $metadata = array())
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
        $this->metadata = $metadata;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
    }
}
