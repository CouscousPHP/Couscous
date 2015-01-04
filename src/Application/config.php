<?php

use Interop\Container\ContainerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

return [

    'steps' => [
        'Couscous\Module\Core\Step\ClearTargetDirectory',
        'Couscous\Module\Config\Step\SetDefaultConfig',
        'Couscous\Module\Config\Step\LoadConfig',
        'Couscous\Module\Config\Step\OverrideBaseUrlForPreview',
        'Couscous\Module\Scripts\Step\ExecuteBeforeScripts',
        'Couscous\Module\Template\Step\UseDefaultTemplate',
        'Couscous\Module\Template\Step\FetchRemoteTemplate',
        'Couscous\Module\Template\Step\ValidateTemplateDirectory',
        'Couscous\Module\Bower\Step\RunBowerInstall',
        'Couscous\Module\Template\Step\LoadAssets',
        'Couscous\Module\Markdown\Step\LoadMarkdownFiles',
        'Couscous\Module\Markdown\Step\ParseMarkdownFrontMatter',
        'Couscous\Module\Markdown\Step\ProcessMarkdownFileName',
        'Couscous\Module\Markdown\Step\RewriteMarkdownLinks',
        'Couscous\Module\Markdown\Step\RenderMarkdown',
        'Couscous\Module\Template\Step\AddPageListToLayoutVariables',
        'Couscous\Module\Template\Step\ProcessTwigLayouts',
        'Couscous\Module\Core\Step\WriteFiles',
        'Couscous\Module\Scripts\Step\ExecuteAfterScripts',
    ],

    'Couscous\Generator' => DI\object()
        ->constructorParameter('steps', DI\link('steps.instances')),

    'steps.instances' => DI\factory(function (ContainerInterface $c) {
        return array_map(function ($class) use ($c) {
            return $c->get($class);
        }, $c->get('steps'));
    }),

    'application' => DI\factory(function (ContainerInterface $c) {
        $application = new Application('Couscous');

        $application->add($c->get('Couscous\Application\Cli\GenerateCommand'));
        $application->add($c->get('Couscous\Application\Cli\PreviewCommand'));
        $application->add($c->get('Couscous\Application\Cli\DeployCommand'));
        $application->add($c->get('Couscous\Application\Cli\ClearCommand'));

        return $application;
    }),

    'Mni\FrontYAML\Parser' => DI\object()
        ->constructorParameter('markdownParser', DI\link('Mni\FrontYAML\Markdown\MarkdownParser')),
    'Mni\FrontYAML\Markdown\MarkdownParser' => DI\object('Mni\FrontYAML\Bridge\Parsedown\ParsedownParser')
        ->constructor(DI\link('ParsedownExtra')),

    'Symfony\Component\Console\Logger\ConsoleLogger' => DI\object()
        ->constructorParameter('verbosityLevelMap', [
            // Custom verbosity map
            LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::ALERT => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::CRITICAL => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::ERROR => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::WARNING => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO => OutputInterface::VERBOSITY_VERBOSE,
            LogLevel::DEBUG => OutputInterface::VERBOSITY_VERY_VERBOSE,
        ]),

];
