<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Step;

/**
 * Add ids to <hN> tags.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
class CreateHeadingIds implements Step
{
    /**
     * @var array
     */
    private $ids = [];

    /**
     * @param Project $project
     */
    public function __invoke(Project $project)
    {
        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');

        foreach ($htmlFiles as $htmlFile) {
            $htmlFile->content = $this->render($htmlFile->getContent());
        }
    }

    private function reset()
    {
        $this->ids = [];
    }

    private function render($content)
    {
        $this->reset();

        return preg_replace_callback(
            '/<(h[0-6])>([^<]+)/',
            [$this, 'addAttributeId'],
            $content
        );
    }

    private function addAttributeId(array $matches)
    {
        $id = $this->slugfy($matches[2]);
        if (!isset($this->ids[$id])) {
            $this->ids[$id] = 0;
        }

        $replacement = sprintf(
            '<%s id="%s">%s',
            $matches[1],
            ($this->ids[$id] > 0 ? $id.'-'.$this->ids[$id] : $id),
            $matches[2]
        );

        $this->ids[$id]++;

        return $replacement;
    }

    private function slugfy($text)
    {
        $slug = trim($text);
        $slug = strtr($slug, ' ', '-');
        $slug = strtolower($slug);

        return preg_replace('/[^a-z0-9_-]/', '', $slug);
    }
}
