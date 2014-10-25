<?php

namespace Couscous\Model;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class HtmlFile extends File
{
    /**
     * Array of custom variables that can be used to render templates or whatever.
     *
     * @var array
     */
    public $customVariables = array();

    /**
     * @var string
     */
    public $content;

    public function __construct($relativeFilename, $content, array $customVariables = array())
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
        $this->customVariables = $customVariables;
    }

    public function getContent()
    {
        return $this->content;
    }
}
