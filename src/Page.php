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
     * @var string
     */
    public $filename;

    /**
     * Page title.
     *
     * @var string
     */
    public $title;

    /**
     * Content of the page.
     *
     * @var string
     */
    public $content;

    public function __construct($filename, $content)
    {
        $this->filename = $filename;
        $this->content = $content;
    }
}
