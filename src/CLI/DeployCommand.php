<?php

namespace Couscous\CLI;

use Couscous\Config;
use Couscous\GenerationHelper;
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
        $sourceDirectory = $input->getArgument('source');
        $repositoryUrl = trim(shell_exec('git config --get remote.origin.url'));
        $targetBranch = $input->getOption('branch');

        $config = Config::fromYaml($sourceDirectory . '/couscous.yml');

        $generation = new GenerationHelper(
            $config,
            $sourceDirectory,
            getcwd() . '/.couscous/generated',
            getcwd() . '/.couscous',
            $output
        );

        // Create the directories
        $filesystem = new Filesystem();
        if (!$filesystem->exists($generation->targetDirectory)) {
            $filesystem->mkdir($generation->targetDirectory);
        }
        if ($filesystem->exists($generation->tempDirectory)) {
            $filesystem->remove($generation->tempDirectory);
        }
        $filesystem->mkdir($generation->tempDirectory);

        // Generate the website
        $generator = new Generator();
        $generator->generate($generation);

        $output->writeln('');

        // Publish it
        $publisher = new Publisher();
        $publisher->publish($generation, $generation->targetDirectory, $repositoryUrl, $targetBranch);
    }
}
