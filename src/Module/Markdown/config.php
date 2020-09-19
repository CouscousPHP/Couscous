<?php

return [

    Mni\FrontYAML\Parser::class => DI\object()
        ->constructorParameter('markdownParser', DI\get(Mni\FrontYAML\Markdown\MarkdownParser::class)),

    Mni\FrontYAML\Markdown\MarkdownParser::class => DI\object(Mni\FrontYAML\Bridge\Parsedown\ParsedownParser::class)
        ->constructor(DI\get('ParsedownExtra')),

];
