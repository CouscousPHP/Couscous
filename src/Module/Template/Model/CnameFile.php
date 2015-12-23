<?php

namespace Couscous\Module\Template\Model;

use Couscous\Model\File;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class CnameFile extends File
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
