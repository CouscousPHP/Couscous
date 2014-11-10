<?php

namespace Couscous\Model;

/**
 * Website template.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Template
{
    /**
     * @var string
     */
    public $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }
}
