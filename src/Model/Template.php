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

    /**
     * Variables made available in layouts.
     * @var array
     */
    public $layoutVariables = array();

    public function __construct($directory)
    {
        $this->directory = $directory;
    }
}
