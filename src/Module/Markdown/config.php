<?php

return [

    'Mni\FrontYAML\Parser' => DI\object()
        ->constructorParameter('markdownParser', DI\get('Mni\FrontYAML\Markdown\MarkdownParser')),

    'Mni\FrontYAML\Markdown\MarkdownParser' => DI\object('Mni\FrontYAML\Bridge\Parsedown\ParsedownParser')
        ->constructor(DI\get('ParsedownExtra')),

];
