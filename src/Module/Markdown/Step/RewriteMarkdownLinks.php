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
    const MARKDOWN_LINK_REGEX  = '#\[([^\]]+)\]\(([^\)]+)\.md\)#';
    const REGEX_REPLACEMENT    = '[$1]($2.html)';
    const UPPERCASE_LINK_REGEX = '#\[(?:[^\]]+)\]\((.+[\/])?([A-Z0-9_-]+\.md)\)#';

    public function __invoke(Project $project)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $file) {
            $content = preg_replace_callback(
                self::UPPERCASE_LINK_REGEX,
                array($this, 'replaceUppercase'),
                $file->content
            );
            $file->content = preg_replace(self::MARKDOWN_LINK_REGEX, self::REGEX_REPLACEMENT, $content);
        }
    }

    private function replaceUppercase(array $matches)
    {
        $filename = strtolower($matches[2]);
        $filename = str_replace('.md', '.html', $filename);
        if ($filename == 'readme.html') {
            $filename = 'index.html';
        }

        return str_replace($matches[2].')', $filename.')', $matches[0]);
    }
}
