<?php

use Interop\Container\ContainerInterface;

return array(

    'steps.classes' => array(
        'Couscous\Step\ClearTargetDirectory',
        'Couscous\Step\Config\SetDefaultConfig',
        'Couscous\Step\Config\LoadConfig',
        'Couscous\Step\OverrideBaseUrl',
        'Couscous\Step\ExecuteBeforeScripts',
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
        'Couscous\Step\ExecuteAfterScripts',
    ),
    'steps' => DI\factory(function (ContainerInterface $c) {
        return array_map(function ($class) use ($c) {
            return $c->get($class);
        }, $c->get('steps.classes'));
    }),

    'Couscous\Generator' => DI\object()
        ->constructorParameter('steps', DI\link('steps')),

);
