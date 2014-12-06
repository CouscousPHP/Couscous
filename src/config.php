<?php

use Interop\Container\ContainerInterface;

return array(

    'steps.classes' => array(
        'Couscous\Step\ClearTargetDirectory',
        'Couscous\Step\Config\SetDefaultConfig',
        'Couscous\Step\Config\LoadConfig',
        'Couscous\Step\Config\OverrideBaseUrlForPreview',
        'Couscous\Step\Scripts\ExecuteBeforeScripts',
        'Couscous\Step\Template\UseDefaultTemplate',
        'Couscous\Step\Template\InitTemplate',
        'Couscous\Step\Assets\RunBowerInstall',
        'Couscous\Step\Template\LoadAssets',
        'Couscous\Step\Markdown\LoadMarkdownFiles',
        'Couscous\Step\Markdown\ParseMarkdownFrontMatter',
        'Couscous\Step\Markdown\ProcessMarkdownFileName',
        'Couscous\Step\Markdown\ProcessMarkdownLinks',
        'Couscous\Step\Markdown\RenderMarkdown',
        'Couscous\Step\Template\AddPageListToLayoutVariables',
        'Couscous\Step\Template\ProcessTwigLayouts',
        'Couscous\Step\WriteFiles',
        'Couscous\Step\Scripts\ExecuteAfterScripts',
    ),
    'steps' => DI\factory(function (ContainerInterface $c) {
        return array_map(function ($class) use ($c) {
            return $c->get($class);
        }, $c->get('steps.classes'));
    }),

    'Couscous\Generator' => DI\object()
        ->constructorParameter('steps', DI\link('steps')),

    'Mni\FrontYAML\Parser' => DI\object()
        ->constructorParameter('markdownParser', DI\link('Mni\FrontYAML\Markdown\MarkdownParser')),
    'Mni\FrontYAML\Markdown\MarkdownParser' => DI\object('Mni\FrontYAML\Bridge\Parsedown\ParsedownParser')
        ->constructor(DI\link('ParsedownExtra')),

);
