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
     * Directory containing the template files.
     * @var string
     */
    public $directory;

    /**
     * Directory containing the public files.
     * @var string
     */
    public $publicDirectory;

    /**
     * Variables made available in templates.
     * @var array
     */
    public $templateVariables = array();

    public function __construct($directory, $publicDirectory)
    {
        $this->directory = $directory;
        $this->publicDirectory = $publicDirectory;
    }
}