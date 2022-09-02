<?php
declare(strict_types = 1);

return [

    Mni\FrontYAML\Parser::class => DI\autowire()
        ->constructorParameter('markdownParser', DI\get(Mni\FrontYAML\Markdown\MarkdownParser::class)),

    Mni\FrontYAML\Markdown\MarkdownParser::class => DI\create(Mni\FrontYAML\Bridge\Parsedown\ParsedownParser::class)
        ->constructor(DI\get('ParsedownExtra')),

];
