<?php

namespace Couscous\Event\Listener;

use Couscous\Model\Project;
use Couscous\Step;
use League\Event\EventInterface;

/**
 * Before Parse Event Listener
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class BeforeParseListener extends BaseListener
{

    /**
     * Handle the event.
     *
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $project = func_get_arg(1)['project'];

        var_dump('before_parse');

        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $file) {
            $file->content = str_replace('Couscous', '**CakePlugins**', $file->content);
        }
    }
}
