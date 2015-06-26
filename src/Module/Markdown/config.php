<?php

return [

    'steps.preprocessing' => DI\add([
        DI\get('Couscous\Module\Markdown\Step\LoadMarkdownFiles'),
        DI\get('Couscous\Module\Markdown\Step\ParseMarkdownFrontMatter'),
        DI\get('Couscous\Module\Markdown\Step\ProcessMarkdownFileName'),
        DI\get('Couscous\Module\Markdown\Step\RewriteMarkdownLinks'),
        DI\get('Couscous\Module\Markdown\Step\RenderMarkdown'),
        DI\get('Couscous\Module\Markdown\Step\CreateHeadingIds'),
    ]),

    'Mni\FrontYAML\Parser' => DI\object()
        ->constructorParameter('markdownParser', DI\get('Mni\FrontYAML\Markdown\MarkdownParser')),

    'Mni\FrontYAML\Markdown\MarkdownParser' => DI\object('Mni\FrontYAML\Bridge\Parsedown\ParsedownParser')
        ->constructor(DI\get('ParsedownExtra')),

];
