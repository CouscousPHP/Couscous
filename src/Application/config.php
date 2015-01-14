<?php

use Interop\Container\ContainerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Application;
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

    'steps' => DI\factory(function (ContainerInterface $c) {
        return array_merge(
            $c->get('steps.init'),
            $c->get('steps.before'),
            $c->get('steps.preprocessing'),
            $c->get('steps.postprocessing'),
            $c->get('steps.after')
        );
    }),

    'Couscous\Generator' => DI\object()
        ->constructorParameter('steps', DI\link('steps')),

    'application' => DI\factory(function (ContainerInterface $c) {
        $application = new Application('Couscous');

        $application->add($c->get('Couscous\Application\Cli\GenerateCommand'));
        $application->add($c->get('Couscous\Application\Cli\PreviewCommand'));
        $application->add($c->get('Couscous\Application\Cli\DeployCommand'));
        $application->add($c->get('Couscous\Application\Cli\ClearCommand'));
        $application->add($c->get('Couscous\Application\Cli\TravisAutoDeployCommand'));

        return $application;
    }),

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
