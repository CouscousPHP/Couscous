<?php

use Interop\Container\ContainerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

return [

    // Generation steps are added by modules
    'steps.init' => [
    ],
    'steps.before' => [
    ],
    'steps.preprocessing' => [
    ],
    'steps.postprocessing' => [
    ],
    'steps.after' => [
    ],

    'steps' => function (ContainerInterface $c) {
        return array_merge(
            $c->get('steps.init'),
            $c->get('steps.before'),
            $c->get('steps.preprocessing'),
            $c->get('steps.postprocessing'),
            $c->get('steps.after')
        );
    },

    'Couscous\Generator' => DI\object()
        ->constructorParameter('steps', DI\get('steps')),

    'application' => DI\object('Symfony\Component\Console\Application')
        ->method('add', DI\get('Couscous\Application\Cli\GenerateCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\PreviewCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\DeployCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\ClearCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\TravisAutoDeployCommand'))
        ->method('add', DI\get('Couscous\Application\Cli\InitTemplateCommand')),

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
