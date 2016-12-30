<?php

namespace Couscous\Application\Cli;

use Couscous\CommandRunner\Git;
use Couscous\Deployer;
use Couscous\Generator;
use Couscous\Model\Project;
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
     * @var Deployer
     */
    private $deployer;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Git
     */
    private $git;

    public function __construct(Generator $generator, Deployer $deployer, Filesystem $filesystem, Git $git)
    {
        $this->generator = $generator;
        $this->deployer = $deployer;
        $this->filesystem = $filesystem;
        $this->git = $git;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        try {
            $remoteUrl = $this->git->getRemoteUrl();
        } catch (\Exception $e) {
            $remoteUrl = null;
        }

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
                'repository',
                null,
                InputOption::VALUE_REQUIRED,
                'Target repository in which to deploy the website.',
                $remoteUrl
            )
            ->addOption(
                'branch',
                null,
                InputOption::VALUE_REQUIRED,
                'Target branch in which to deploy the website.'
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
        $sourceDirectory = $input->getArgument('source');
        $repositoryUrl = $input->getOption('repository');
        $targetBranch = $input->getOption('branch');
        $cliConfig = $input->getOption('config');

        $project = new Project($sourceDirectory, getcwd().'/.couscous/generated');

        $project->metadata['cliConfig'] = $cliConfig;

        // Generate the website
        $this->generator->generate($project, $output);

        // If no branch was provided, use the configured one or the default
        if (!$targetBranch) {
            $targetBranch = isset($project->metadata['branch']) ? $project->metadata['branch'] : 'gh-pages';
        }

        $output->writeln('');

        // Deploy it
        $this->deployer->deploy($project, $output, $repositoryUrl, $targetBranch);
    }
}
