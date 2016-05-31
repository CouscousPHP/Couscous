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
     * The document title.
     *
     * @var string|null
     */
    private $title;

    /**
     * Array of headers. Starts at level 2.
     *
     * @var array<array<'level', 'text'>>
     */
    private $headers = [];

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param int $level Header level (from 1 to 6)
     * @param string $text
     */
    public function addHeader($level, $text)
    {
        if ($level === 1) {
            if (!$this->title && !$this->headers) {
                $this->title = $text;
            }
            return;
        }

        $this->headers[] = [$level, $text];
    }

    /**
     * Export the table of contents to an HTML list.
     *
     * The list is nested: sub-levels are embedded in list items.
     *
     * @return string
     */
    public function toHtmlList()
    {
        $html = '';

        $currentLevel = 1;
        foreach ($this->headers as $item) {
            list($level, $text) = $item;

            if ($level > $currentLevel) {
                // Enter a sub-level
                $html .= '<ul>';
                $currentLevel++;
                while ($level > $currentLevel) {
                    $html .= '<li><ul>';
                    $currentLevel++;
                }
            } elseif ($level < $currentLevel) {
                // Close a sub-level
                $html .= '</li>';
                while ($level < $currentLevel) {
                    $html .= '</ul></li>';
                    $currentLevel--;
                }
            } else {
                $html .= '</li>';
            }

            $html .= sprintf('<li>%s', $text);
        }

        // Close nesting left
        while ($currentLevel > 1) {
            $html .= '</li></ul>';
            $currentLevel--;
        }

        return $html;
    }
}
