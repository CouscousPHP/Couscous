<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Rewrites links from *.md to *.html.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RewriteMarkdownLinks implements Step
{
    // OMG regexes...
    const MARKDOWN_LINK_REGEX = '#\[([^\]]+)\]\(([^\)]+)\.md\)#';
    const REGEX_REPLACEMENT   = '[$1]($2.html)';

    public function __invoke(Project $project)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $file) {
            $file->content = preg_replace(
                self::MARKDOWN_LINK_REGEX,
                self::REGEX_REPLACEMENT,
                $file->content
            );
        }
    }
}
