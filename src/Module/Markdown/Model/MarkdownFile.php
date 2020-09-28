<?php
declare(strict_types = 1);

namespace Couscous\Module\Markdown\Model;

use Couscous\Model\File;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MarkdownFile extends File
{
    /**
     * @var string
     */
    public $content;

    public function __construct(string $relativeFilename, string $content)
    {
        $this->content = $content;

        parent::__construct($relativeFilename);
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
