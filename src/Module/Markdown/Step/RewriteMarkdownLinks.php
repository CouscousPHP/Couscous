<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Step;

/**
 * Rewrites links from *.md to *.html.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RewriteMarkdownLinks implements Step
{
    /**
     * OMG regexes...
     *
     * @link https://regex101.com/
     */
    const MARKDOWN_LINK_REGEX = '/\[(?:[^\]]+)\]\(([^\)]+\/)?([A-Za-z0-9_\.\-]+\.md)([^.\)][^\)]*)?\)/';

    public function __invoke(Project $project)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $file) {
            $pattern = self::MARKDOWN_LINK_REGEX;
            $subject = $file->content;

            $file->content = preg_replace_callback($pattern, [$this, 'replaceFilename'], $subject);
        }
    }

    private function replaceFilename(array $matches)
    {
        $filename = strtolower($matches[2]);
        $filename = str_replace('.md', '.html', $filename);
        if ($filename == 'readme.html') {
            $filename = 'index.html';
        }

        $pattern = '/\((.+)?\b'.$matches[2].'\b(.+)?\)/';
        $replacement = '(${1}'.$filename.'${2})';

        return preg_replace($pattern, $replacement, $matches[0]);
    }
}
