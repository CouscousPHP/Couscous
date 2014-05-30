<?php

namespace Couscous;

/**
 * Represents a page of the website.
 *
 * Extends stdClass so that properties can be added by processors at will.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Page extends \stdClass
{
    /**
     * File name (no path).
     *
     * @var string
     */
    public $filename;

    /**
     * Content of the page.
     *
     * @var string
     */
    public $content;

    /**
     * Template to use to render the page.
     *
     * @var string
     */
    public $template = 'page';

    /**
     * @param string $filename
     * @param string $content
     * @param array  $variables Arbitrary variables that we want to add to the page.
     */
    public function __construct($filename, $content, array $variables)
    {
        $this->filename = $filename;
        $this->content = $content;

        foreach ($variables as $name => $variable) {
            $this->$name = $variable;
        }
    }
}
