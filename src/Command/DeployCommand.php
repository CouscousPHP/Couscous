<?php

namespace Couscous\Command;

use Couscous\Model\Config;
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
     * @var Generator
     */
    private $generator;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Generator $generator, Publisher $publisher, Filesystem $filesystem)
    {
        $this->generator = $generator;
        $this->publisher = $publisher;
        $this->filesystem = $filesystem;

        parent::__construct();
    }

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
        if (! $this->filesystem->exists($generation->targetDirectory)) {
            $this->filesystem->mkdir($generation->targetDirectory);
        }
        if ($this->filesystem->exists($generation->tempDirectory)) {
            $this->filesystem->remove($generation->tempDirectory);
        }
        $this->filesystem->mkdir($generation->tempDirectory);

        // Generate the website
        $this->generator->generate($generation);

        $output->writeln('');

        // Publish it
        $this->publisher->publish($generation, $generation->targetDirectory, $repositoryUrl, $targetBranch);
    }
}
