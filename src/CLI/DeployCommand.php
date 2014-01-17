<?php

namespace Couscous\CLI;

use Couscous\Config;
use Couscous\Generator;
use Couscous\Publisher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Generate and deploy the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DeployCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('deploy')
            ->setDescription('Generate and deploy the website')
            ->addArgument(
                'source',
                InputArgument::OPTIONAL,
                'Repository you want to generate.',
                getcwd()
            )
            ->addOption(
                'branch',
                null,
                InputOption::VALUE_REQUIRED,
                'Target branch in which to publish the website.',
                'gh-pages'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem();

        $sourceDirectory = $input->getArgument('source');
        $targetBranch = $input->getOption('branch');

        $repositoryUrl = trim(shell_exec('git config --get remote.origin.url'));

        $config = Config::fromYaml($sourceDirectory . '/couscous.yml');

        // Create the directories
        $targetDirectory = '.couscous/generated';
        if (! $filesystem->exists($targetDirectory)) {
            $filesystem->mkdir($targetDirectory);
        }
        $tempDirectory = '.couscous/deploy';
        if ($filesystem->exists($tempDirectory)) {
            $filesystem->remove($tempDirectory);
        }
        $filesystem->mkdir($tempDirectory);

        // Generate the website
        $generator = new Generator();
        $generator->generate($config, $sourceDirectory, $targetDirectory, $output);

        $output->writeln('');

        // Publish it
        $publisher = new Publisher();
        $publisher->publish($targetDirectory, $repositoryUrl, $targetBranch, $tempDirectory, $output);
    }
}
