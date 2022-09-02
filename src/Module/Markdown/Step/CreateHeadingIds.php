<?php
declare(strict_types = 1);

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
     * @var array<string, int>
     */
    private $ids = [];

    public function __invoke(Project $project): void
    {
        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType(HtmlFile::class);

        foreach ($htmlFiles as $htmlFile) {
            $htmlFile->content = $this->render($htmlFile->getContent());
        }
    }

    private function reset(): void
    {
        $this->ids = [];
    }

    private function render(string $content): string
    {
        $this->reset();

        return preg_replace_callback(
            '/<(h[0-6])>([^<]+)/',
            [$this, 'addAttributeId'],
            $content
        );
    }

    /**
     * @param array<int, string> $matches
     */
    private function addAttributeId(array $matches): string
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

    private function slugfy(string $text): string
    {
        $slug = trim($text);
        $slug = str_replace(' ', '-', $slug);
        $slug = strtolower($slug);

        return preg_replace('/[^a-z0-9_-]/', '', $slug);
    }
}
