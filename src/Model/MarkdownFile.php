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

    public function __construct($relativeFilename, $content)
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
