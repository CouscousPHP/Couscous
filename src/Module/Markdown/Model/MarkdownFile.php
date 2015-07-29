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

    public function __construct($relativeFilename, $content)
    {
        $this->content = $content;

        parent::__construct($relativeFilename);
    }

    public function getContent()
    {
        return $this->content;
    }
}
