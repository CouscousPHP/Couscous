<?php

return [

    'steps.preprocessing' => DI\add([
        DI\link('Couscous\Module\Markdown\Step\LoadMarkdownFiles'),
        DI\link('Couscous\Module\Markdown\Step\ParseMarkdownFrontMatter'),
        DI\link('Couscous\Module\Markdown\Step\ProcessMarkdownFileName'),
        DI\link('Couscous\Module\Markdown\Step\RewriteMarkdownLinks'),
        DI\link('Couscous\Module\Markdown\Step\RenderMarkdown'),
        DI\link('Couscous\Module\Markdown\Step\CreateHeadingIds'),
    ]),

    'Mni\FrontYAML\Parser' => DI\object()
        ->constructorParameter('markdownParser', DI\link('Mni\FrontYAML\Markdown\MarkdownParser')),

    'Mni\FrontYAML\Markdown\MarkdownParser' => DI\object('Mni\FrontYAML\Bridge\Parsedown\ParsedownParser')
        ->constructor(DI\link('ParsedownExtra')),

];
