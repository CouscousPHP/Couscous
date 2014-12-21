<?php

namespace Couscous\Module\Markdown\Model;

/**
 * Table of contents of the document.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TableOfContents
{
    /**
     * Array of headers indexed by the level.
     *
     * @var string[][]
     */
    private $headers = [];

    /**
     * @return string
     */
    public function getTitle()
    {
        return reset($this->headers[1]);
    }

    /**
     * @param int $level Header level (from 1 to 6)
     * @return string[]
     */
    public function getHeaders($level)
    {
        return $this->headers[$level];
    }

    /**
     * @param int      $level   Header level (from 1 to 6)
     * @param string[] $headers
     */
    public function setHeaders($level, array $headers)
    {
        $this->headers[$level] = $headers;
    }

    /**
     * @param string[][] $headers
     */
    public function setAllHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return string[][]
     */
    public function getAllHeaders()
    {
        return $this->headers;
    }
}
