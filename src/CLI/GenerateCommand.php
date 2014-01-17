<?php

namespace Couscous\CLI;

use Couscous\Config;
use Couscous\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate the website')
            ->addArgument(
                'source',
                InputArgument::OPTIONAL,
                'Repository you want to generate.',
                getcwd()
            )
            ->addOption(
                'target',
                null,
                InputOption::VALUE_REQUIRED,
                'Target directory in which to generate the files.',
                getcwd() . '/.couscous'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceDirectory = $input->getArgument('source');
        $targetDirectory = $input->getOption('target');

        $config = Config::fromYaml($sourceDirectory . '/couscous.yml');

        $generator = new Generator();
        $generator->generate($config, $sourceDirectory, $targetDirectory, $output);
    }
}
