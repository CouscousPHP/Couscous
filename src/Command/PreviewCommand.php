<?php

namespace Couscous\Command;

use Couscous\Generator;
use Couscous\Model\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Preview the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PreviewCommand extends Command
{
    /**
     * @var Generator
     */
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('preview')
            ->setDescription('Starts a webserver to preview the website')
            ->addArgument(
                'address',
                InputArgument::OPTIONAL,
                'Address:port',
                'localhost:8000'
            )
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
                getcwd() . '/.couscous/generated'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->isSupported()) {
            $output->writeln('<error>PHP 5.4 or above is required to run the internal webserver</error>');
            return;
        }

        $sourceDirectory = $input->getArgument('source');
        $targetDirectory = $input->getOption('target');

        $lastGenerationDate = date('Y-m-d H:i:s');
        $this->generateWebsite($output, $sourceDirectory, $targetDirectory);

        $this->startWebServer($input, $output, $targetDirectory);

        // Watch for changes
        while (true) {
            if ($this->hasChanges($sourceDirectory, $lastGenerationDate)) {
                $output->writeln('');
                $output->writeln('<info>File changes detected, regenerating</info>');
                $lastGenerationDate = date('Y-m-d H:i:s');
                $this->generateWebsite($output, $sourceDirectory, $targetDirectory, true);
            }

            sleep(1);
        }
    }

    private function generateWebsite(
        OutputInterface $output,
        $sourceDirectory,
        $targetDirectory,
        $regenerate = false
    ) {
        $repository = new Repository($sourceDirectory, $targetDirectory);

        // Override baseUrl since we are running it ourselves
        $repository->overrideBaseUrl = '';

        $repository->regenerate = $regenerate;

        $this->generator->generate($repository, $output);
    }

    private function startWebServer(InputInterface $input, OutputInterface $output, $targetDirectory)
    {
        $builder = new ProcessBuilder(array(PHP_BINARY, '-S', $input->getArgument('address')));
        $builder->setWorkingDirectory($targetDirectory);
        $builder->setTimeout(null);
        $process = $builder->getProcess();
        $process->start();

        $output->writeln(sprintf("Server running on <info>%s</info>\n", $input->getArgument('address')));
    }

    private function hasChanges($sourceDirectory, $lastCheckDate)
    {
        $changedFiles = new Finder();
        $changedFiles->files()->in($sourceDirectory)->date('after ' . $lastCheckDate);

        return (count($changedFiles) > 0);
    }

    private function isSupported()
    {
        if (version_compare(phpversion(), '5.4.0', '<')) {
            return false;
        }
        return true;
    }
}
