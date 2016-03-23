<?php

namespace Couscous\Application\Cli;

use Couscous\Generator;
use Couscous\Model\Project;
use RuntimeException;
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
                '127.0.0.1:8000'
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
                getcwd().'/.couscous/generated'
            )
            ->addOption(
                'livereload',
                null,
                InputOption::VALUE_REQUIRED,
                'If set livereload server is launched from the specified path (global livereload by default)',
                exec('which livereload')
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isSupported()) {
            $output->writeln('<error>PHP 5.4 or above is required to run the internal webserver</error>');

            return 1;
        }

        if (!is_executable($input->getOption('livereload'))) {
            $output->writeln('<error>Impossible to launch Livereload, did you forgot to install it with "npm install -g livereload"?</error>');

            return 1;
        }

        $sourceDirectory = $input->getArgument('source');
        $targetDirectory = $input->getOption('target');

        $watchlist = $this->generateWebsite($output, $sourceDirectory, $targetDirectory);

        $this->startLivereload($input->getOption('livereload'), $output, $sourceDirectory, $targetDirectory);

        $serverProcess = $this->startWebServer($input, $output, $targetDirectory);

        // Watch for changes
        while ($serverProcess->isRunning()) {
            $files = $watchlist->getChangedFiles();
            if (count($files) > 0) {
                $output->writeln('');
                $output->write(sprintf('<comment>%d file(s) changed: regenerating</comment>', count($files)));
                $output->writeln(sprintf(' (%s)', $this->fileListToDisplay($files, $sourceDirectory)));

                $watchlist = $this->generateWebsite($output, $sourceDirectory, $targetDirectory, true);
            }

            sleep(1);
        }

        throw new RuntimeException('The HTTP server has stopped');
    }

    private function generateWebsite(
        OutputInterface $output,
        $sourceDirectory,
        $targetDirectory,
        $regenerate = false
    ) {
        $project = new Project($sourceDirectory, $targetDirectory);

        $project->metadata['preview'] = true;

        $project->regenerate = $regenerate;

        $this->generator->generate($project, $output);

        return $project->watchlist;
    }

    private function startWebServer(InputInterface $input, OutputInterface $output, $targetDirectory)
    {
        $builder = new ProcessBuilder([PHP_BINARY, '-S', $input->getArgument('address')]);
        $builder->setWorkingDirectory($targetDirectory);
        $builder->setTimeout(null);

        $process = $builder->getProcess();
        $process->start();

        $output->writeln(sprintf('Server running on <comment>%s</comment>', $input->getArgument('address')));

        return $process;
    }

    private function startLivereload($executablePath, OutputInterface $output, $sourceDirectory, $targetDirectory)
    {
        $builder = new ProcessBuilder([$executablePath, $targetDirectory, '-w', '1']);
        $builder->setWorkingDirectory($sourceDirectory);
        $builder->setTimeout(null);

        $process = $builder->getProcess();
        $process->start();

        $output->writeln('<info>Livereload launched!</info>');
    }

    private function isSupported()
    {
        if (version_compare(phpversion(), '5.4.0', '<')) {
            return false;
        }

        return true;
    }

    private function fileListToDisplay(array $files, $sourceDirectory)
    {
        $files = array_map(function ($file) use ($sourceDirectory) {
            return substr($file, strlen($sourceDirectory) + 1);
        }, $files);

        $str = implode(', ', $files);

        if (strlen($str) > 60) {
            $str = substr($str, 0, 60).'…';
        }

        return $str;
    }
}
