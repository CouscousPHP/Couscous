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
     * Metadata/variables made available in layouts.
     *
     * @var array
     */
    public $metadata = array();

    public function __construct($directory)
    {
        $this->directory = $directory;
    }
}
