<?php

namespace Couscous\CLI;

use Couscous\Config;
use Couscous\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Preview the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PreviewCommand extends Command
{
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
                getcwd() . '/.couscous'
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

        $config = Config::fromYaml($sourceDirectory . '/couscous.yml');

        // Override baseUrl since we are running it ourselves
        $config->baseUrl = '';

        $generator = new Generator();
        $generator->generate($config, $sourceDirectory, $targetDirectory, $output);

        $output->writeln(sprintf("Server running on <info>%s</info>\n", $input->getArgument('address')));

        $builder = new ProcessBuilder(array(PHP_BINARY, '-S', $input->getArgument('address')));
        $builder->setWorkingDirectory($targetDirectory);
        $builder->setTimeout(null);
        $builder->getProcess()->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });
    }

    /**
     * {@inheritdoc}
     */
    private function isSupported()
    {
        if (version_compare(phpversion(), '5.4.0', '<')) {
            return false;
        }
        return true;
    }
}
