<?php

use Interop\Container\ContainerInterface;

return [

    'Mni\FrontYAML\Parser' => DI\object()
        ->constructorParameter('markdownParser', DI\get('Mni\FrontYAML\Markdown\MarkdownParser')),

    'Mni\FrontYAML\Markdown\MarkdownParser' => DI\object('Mni\FrontYAML\Bridge\CommonMark\CommonMarkParser')
        ->constructor(DI\get('League\CommonMark\Converter')),

    'League\CommonMark\Converter' => DI\object()
        ->constructor(DI\get('League\CommonMark\DocParser'), DI\get('League\CommonMark\HtmlRenderer')),

    'League\CommonMark\DocParser' => DI\object()
        ->constructor(DI\get('League\CommonMark\Environment')),

    'League\CommonMark\HtmlRenderer' => DI\object()
        ->constructor(DI\get('League\CommonMark\Environment')),

    'League\CommonMark\Environment' => function (ContainerInterface $c) {
        $env = \League\CommonMark\Environment::createCommonMarkEnvironment();
        $env->addExtension($c->get('Webuni\CommonMark\TableExtension\TableExtension'));
        $env->addExtension($c->get('Webuni\CommonMark\AttributesExtension\AttributesExtension'));

        return $env;
    },

    'Webuni\CommonMark\TableExtension\TableExtension' => DI\object(),

    'Webuni\CommonMark\AttributesExtension\AttributesExtension' => DI\object(),
];
