<?php
declare(strict_types = 1);

namespace Couscous\Module\Template\Model;

use Couscous\Model\File;

/**
 * @author Leonardo Ruhland <leoruhland@gmail.com>
 */
class CnameFile extends File
{
    /**
     * @var string
     */
    public $content;

    public function __construct(string $relativeFilename, string $content)
    {
        parent::__construct($relativeFilename);

        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
