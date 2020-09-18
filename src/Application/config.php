<?php

use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

return [

    'steps' => [
        DI\get('Couscous\Module\Config\Step\SetDefaultConfig'),
        DI\get('Couscous\Module\Config\Step\LoadConfig'),
        DI\get('Couscous\Module\Config\Step\OverrideConfigFromCLI'),
        DI\get('Couscous\Module\Config\Step\OverrideBaseUrlForPreview'),

        DI\get('Couscous\Module\Scripts\Step\ExecuteBeforeScripts'),

        DI\get('Couscous\Module\Template\Step\UseDefaultTemplate'),
        DI\get('Couscous\Module\Template\Step\FetchRemoteTemplate'),
        DI\get('Couscous\Module\Template\Step\ValidateTemplateDirectory'),

        DI\get('Couscous\Module\Dependencies\Step\InstallDependencies'),

        DI\get('Couscous\Module\Markdown\Step\LoadMarkdownFiles'),
        DI\get('Couscous\Module\Template\Step\LoadAssets'),
        DI\get('Couscous\Module\Core\Step\AddImages'),
        DI\get('Couscous\Module\Core\Step\AddCname'),

        DI\get('Couscous\Module\Core\Step\AddFileNameToMetadata'),

        DI\get('Couscous\Module\Markdown\Step\ParseMarkdownFrontMatter'),
        DI\get('Couscous\Module\Markdown\Step\ProcessMarkdownFileName'),
        DI\get('Couscous\Module\Markdown\Step\RewriteMarkdownLinks'),
        DI\get('Couscous\Module\Markdown\Step\RenderMarkdown'),
        DI\get('Couscous\Module\Markdown\Step\CreateHeadingIds'),

        DI\get('Couscous\Module\Template\Step\AddPageListToLayoutVariables'),
        DI\get('Couscous\Module\Template\Step\ProcessTwigLayouts'),

        DI\get('Couscous\Module\Template\Step\AddLivereloadSnippet'),

        DI\get('Couscous\Module\Core\Step\ClearTargetDirectory'),
        DI\get('Couscous\Module\Core\Step\WriteFiles'),

        DI\get('Couscous\Module\Scripts\Step\ExecuteAfterScripts'),
    ],

    'Couscous\Generator' => DI\object()
        ->constructorParameter('steps', DI\get('steps')),

    'application' => DI\object('Symfony\Component\Console\Application')
        ->constructor('Couscous', 'dev-master')
        ->method('add', DI\get('Couscous\Application\Cli\GenerateCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\PreviewCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\DeployCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\ClearCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\SelfUpdateCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\TravisAutoDeployCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\InitTemplateCommand')),

    'Symfony\Component\Console\Logger\ConsoleLogger' => DI\object()
        ->constructorParameter('verbosityLevelMap', [
            // Custom verbosity map
            LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::ALERT     => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::CRITICAL  => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::ERROR     => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::WARNING   => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::NOTICE    => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO      => OutputInterface::VERBOSITY_VERBOSE,
            LogLevel::DEBUG     => OutputInterface::VERBOSITY_VERY_VERBOSE,
        ]),

];
