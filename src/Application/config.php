<?php
declare(strict_types = 1);

use Couscous\Application;
use Couscous\Module;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

return [

    'steps' => [
        DI\get(Module\Config\Step\SetDefaultConfig::class),
        DI\get(Module\Config\Step\LoadConfig::class),
        DI\get(Module\Config\Step\OverrideConfigFromCLI::class),
        DI\get(Module\Config\Step\OverrideBaseUrlForPreview::class),

        DI\get(Module\Scripts\Step\ExecuteBeforeScripts::class),

        DI\get(Module\Template\Step\UseDefaultTemplate::class),
        DI\get(Module\Template\Step\FetchRemoteTemplate::class),
        DI\get(Module\Template\Step\ValidateTemplateDirectory::class),

        DI\get(Module\Dependencies\Step\InstallDependencies::class),

        DI\get(Module\Markdown\Step\LoadMarkdownFiles::class),
        DI\get(Module\Template\Step\LoadAssets::class),
        DI\get(Module\Core\Step\AddImages::class),
        DI\get(Module\Core\Step\AddCname::class),

        DI\get(Module\Core\Step\AddFileNameToMetadata::class),

        DI\get(Module\Markdown\Step\ParseMarkdownFrontMatter::class),
        DI\get(Module\Markdown\Step\ProcessMarkdownFileName::class),
        DI\get(Module\Markdown\Step\RewriteMarkdownLinks::class),
        DI\get(Module\Markdown\Step\RenderMarkdown::class),
        DI\get(Module\Markdown\Step\CreateHeadingIds::class),

        DI\get(Module\Template\Step\AddPageListToLayoutVariables::class),
        DI\get(Module\Template\Step\ProcessTwigLayouts::class),

        DI\get(Module\Template\Step\AddLivereloadSnippet::class),

        DI\get(Module\Core\Step\ClearTargetDirectory::class),
        DI\get(Module\Core\Step\WriteFiles::class),

        DI\get(Module\Scripts\Step\ExecuteAfterScripts::class),
    ],

    Couscous\Generator::class => DI\autowire()
        ->constructorParameter('steps', DI\get('steps')),

    'application' => DI\create(Symfony\Component\Console\Application::class)
        ->constructor('Couscous', 'dev-master')
        ->method('add', DI\get(Application\Cli\GenerateCommand::class))
        ->method('add', DI\get(Application\Cli\PreviewCommand::class))
        ->method('add', DI\get(Application\Cli\DeployCommand::class))
        ->method('add', DI\get(Application\Cli\ClearCommand::class))
        ->method('add', DI\get(Application\Cli\SelfUpdateCommand::class))
        ->method('add', DI\get(Application\Cli\TravisAutoDeployCommand::class))
        ->method('add', DI\get(Application\Cli\InitTemplateCommand::class)),

    Symfony\Component\Console\Logger\ConsoleLogger::class => DI\autowire()
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
