<?php

namespace Couscous\Module\Template\Model;

use Couscous\Model\File;

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
     * @var File
     */
    private $wrappedFile;

    public function __construct($relativeFilename, $content, File $wrappedFile = null)
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
        $this->wrappedFile = $wrappedFile;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getMetadata()
    {
        return $this->wrappedFile ? $this->wrappedFile->getMetadata() : parent::getMetadata();
    }
}
