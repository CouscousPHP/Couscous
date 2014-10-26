<?php

use Interop\Container\ContainerInterface;

return array(

    'steps.classes' => array(
        'Couscous\Step\ClearTargetDirectory',
        'Couscous\Step\LoadConfig',
        'Couscous\Step\OverrideBaseUrl',
        'Couscous\Step\ExecuteBeforeScripts',
        'Couscous\Step\Template\InitTemplate',
        'Couscous\Step\Template\LoadPublicFiles',
        'Couscous\Step\Markdown\LoadMarkdownFiles',
        'Couscous\Step\Markdown\ProcessMarkdownFileName',
        'Couscous\Step\Markdown\ProcessMarkdownLinks',
        'Couscous\Step\Markdown\RenderMarkdown',
        'Couscous\Step\Template\ProcessTwigTemplates',
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
