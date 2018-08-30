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
use Symfony\Component\Process\Process;

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
                InputOption::VALUE_OPTIONAL,
                'If set livereload server is launched from the specified path (global livereload by default)'
            )
            ->addOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'If specified will override entries in couscous.yml (key=value)',
                []
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cliConfig = $input->getOption('config');

        if (!$this->isSupported()) {
            $output->writeln('<error>PHP 5.4 or above is required to run the internal webserver</error>');

            return 1;
        }

        $sourceDirectory = $input->getArgument('source');
        $targetDirectory = $input->getOption('target');

        if ($input->hasParameterOption('--livereload')) {
            if ($input->getOption('livereload') === null) {
                $input->setOption('livereload', 'livereload');
            }

            if (!$this->isFound($input->getOption('livereload'))) {
                $output->writeln(
                    '<error>Impossible to launch Livereload, '
                    .'did you forgot to install it with "pip install livereload" (sudo maybe required)?</error>'
                );

                return 1;
            }

            $this->startLivereload($input->getOption('livereload'), $output, $sourceDirectory, $targetDirectory);
        }

        $watchlist = $this->generateWebsite($output, $sourceDirectory, $targetDirectory, $cliConfig);

        $serverProcess = $this->startWebServer($input, $output, $targetDirectory);
        $throwOnServerStop = true;

        if (function_exists('pcntl_signal')) {
            declare(ticks=1);

            $handler = function ($signal) use ($serverProcess, $output, &$throwOnServerStop) {
                $throwOnServerStop = !$this->stopWebServer($serverProcess, $output, $signal);
            };

            foreach ([SIGINT, SIGTERM] as $signal) {
                pcntl_signal($signal, $handler);
            }
        }

        // Watch for changes
        while ($serverProcess->isRunning()) {
            $files = $watchlist->getChangedFiles();
            if (count($files) > 0) {
                $output->writeln('');
                $output->write(sprintf('<comment>%d file(s) changed: regenerating</comment>', count($files)));
                $output->writeln(sprintf(' (%s)', $this->fileListToDisplay($files, $sourceDirectory)));

                $watchlist = $this->generateWebsite($output, $sourceDirectory, $targetDirectory, $cliConfig, true);
            }

            sleep(1);
        }

        if ($throwOnServerStop) {
            throw new RuntimeException('The HTTP server has stopped: '.PHP_EOL.$serverProcess->getErrorOutput());
        }

        return 0;
    }

    private function generateWebsite(
        OutputInterface $output,
        $sourceDirectory,
        $targetDirectory,
        $cliConfig,
        $regenerate = false
    ) {
        $project = new Project($sourceDirectory, $targetDirectory);

        $project->metadata['cliConfig'] = $cliConfig;
        $project->metadata['preview'] = true;

        $project->regenerate = $regenerate;

        $this->generator->generate($project, $output);

        return $project->watchlist;
    }

    private function startWebServer(InputInterface $input, OutputInterface $output, $targetDirectory)
    {
        $processArguments = [PHP_BINARY, '-S', $input->getArgument('address')];

        $process = new Process($processArguments);
        $process->setWorkingDirectory($targetDirectory);
        $process->setTimeout(null);
        $process->start();

        $output->writeln(sprintf('Server running on <comment>http://%s</comment>', $input->getArgument('address')));

        return $process;
    }

    private function stopWebServer(Process $serverProcess, OutputInterface $output, $signal = null)
    {
        $signal = $signal ?: SIGTERM;

        if ($serverProcess->isRunning()) {
            $output->writeln(sprintf('Killing server with signal <comment>%s</comment>', $signal));

            $serverProcess->stop(0, $signal);
        }

        if ($serverProcess->isRunning() === false) {
            $output->writeln(sprintf('Server was killed with signal <comment>%s</comment>', $signal));

            return true;
        }

        $output->writeln(sprintf('Unable to kill the server with signal <comment>%s</comment>', $signal));

        return false;
    }

    private function startLivereload($executablePath, OutputInterface $output, $sourceDirectory, $targetDirectory)
    {
        $processArguments = [$executablePath, $targetDirectory, '-w', '3'];

        $process = new Process($processArguments);
        $process->setWorkingDirectory($sourceDirectory);
        $process->setTimeout(null);
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

    private function isFound($executablePath)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $folders = explode(';', getenv('PATH'));
        } else {
            $folders = explode(':', getenv('PATH'));
        }

        foreach ($folders as $folder) {
            if (is_executable(realpath($folder.'/'.$executablePath))) {
                return true;
            }
        }

        return false;
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
