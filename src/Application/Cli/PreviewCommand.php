<?php
declare(strict_types = 1);

namespace Couscous\Application\Cli;

use Couscous\Generator;
use Couscous\Model\Project;
use Couscous\Model\WatchList\WatchList;
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
    protected function configure(): void
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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var array */
        $cliConfig = $input->getOption('config');

        /** @var string */
        $sourceDirectory = $input->getArgument('source');
        /** @var string */
        $targetDirectory = $input->getOption('target');

        if ($input->hasParameterOption('--livereload')) {
            /** @var ?string */
            $livereload = $input->getOption('livereload');

            if ($livereload === null) {
                $livereload = 'livereload';
            }

            if (!$this->isFound($livereload)) {
                $output->writeln(
                    '<error>Impossible to launch Livereload, '
                    .'did you forgot to install it with "pip install livereload" '
                    .'or "npm install -g livereload"?</error>'
                );

                return 1;
            }

            $this->startLivereload($livereload, $output, $sourceDirectory, $targetDirectory);
        }

        $watchlist = $this->generateWebsite($output, $sourceDirectory, $targetDirectory, $cliConfig);

        $serverProcess = $this->startWebServer($input, $output, $targetDirectory);
        $throwOnServerStop = true;

        if (function_exists('pcntl_signal')) {
            declare(ticks=1);

            $handler = function (int $signal) use ($serverProcess, $output, &$throwOnServerStop): void {
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
        string $sourceDirectory,
        string $targetDirectory,
        array $cliConfig,
        bool $regenerate = false
    ): WatchList {
        $project = new Project($sourceDirectory, $targetDirectory);

        $project->metadata['cliConfig'] = $cliConfig;
        $project->metadata['preview'] = true;

        $project->regenerate = $regenerate;

        $this->generator->generate($project, $output);

        return $project->watchlist;
    }

    private function startWebServer(InputInterface $input, OutputInterface $output, string $targetDirectory): Process
    {
        /** @var string */
        $address = $input->getArgument('address');
        $processArguments = [PHP_BINARY, '-S', $address];

        $process = new Process($processArguments);
        $process->setWorkingDirectory($targetDirectory);
        $process->setTimeout(null);
        $process->start();

        $output->writeln(sprintf('Server running on <comment>http://%s</comment>', $address));

        return $process;
    }

    private function stopWebServer(Process $serverProcess, OutputInterface $output, int $signal = null): bool
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

    private function startLivereload(
        string $executablePath,
        OutputInterface $output,
        string $sourceDirectory,
        string $targetDirectory
    ): void {
        $processArguments = [$executablePath, $targetDirectory, '-w', '3'];

        $process = new Process($processArguments);
        $process->setWorkingDirectory($sourceDirectory);
        $process->setTimeout(null);
        $process->start();

        $output->writeln('<info>Livereload launched!</info>');
    }

    private function isFound(string $executablePath): bool
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $folders = explode(';', (string) getenv('PATH'));
        } else {
            $folders = explode(':', (string) getenv('PATH'));
        }

        foreach ($folders as $folder) {
            if (is_executable(realpath($folder.'/'.$executablePath))) {
                return true;
            }
        }

        return false;
    }

    private function fileListToDisplay(array $files, string $sourceDirectory): string
    {
        $files = array_map(function (string $file) use ($sourceDirectory): string {
            return substr($file, strlen($sourceDirectory) + 1);
        }, $files);

        $str = implode(', ', $files);

        if (strlen($str) > 60) {
            $str = substr($str, 0, 60).'…';
        }

        return $str;
    }
}
