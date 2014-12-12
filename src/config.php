<?php

use Interop\Container\ContainerInterface;

return array(

    'steps.classes' => array(
        'Couscous\Step\ClearTargetDirectory',
        'Couscous\Step\Config\SetDefaultConfig',
        'Couscous\Step\Config\LoadConfig',
        'Couscous\Step\Config\OverrideBaseUrlForPreview',
        'Couscous\Module\Scripts\Step\ExecuteBeforeScripts',
        'Couscous\Step\Template\UseDefaultTemplate',
        'Couscous\Step\Template\FetchRemoteTemplate',
        'Couscous\Step\Template\ValidateTemplateDirectory',
        'Couscous\Module\Bower\Step\RunBowerInstall',
        'Couscous\Step\Template\LoadAssets',
        'Couscous\Module\Markdown\Step\LoadMarkdownFiles',
        'Couscous\Module\Markdown\Step\ParseMarkdownFrontMatter',
        'Couscous\Module\Markdown\Step\ProcessMarkdownFileName',
        'Couscous\Module\Markdown\Step\ProcessMarkdownLinks',
        'Couscous\Module\Markdown\Step\RenderMarkdown',
        'Couscous\Step\Template\AddPageListToLayoutVariables',
        'Couscous\Step\Template\ProcessTwigLayouts',
        'Couscous\Step\WriteFiles',
        'Couscous\Module\Scripts\Step\ExecuteAfterScripts',
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
