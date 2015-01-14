<?php

namespace Couscous\Application\Cli;

use Couscous\Generator;
use Couscous\Model\Project;
use Couscous\Deployer;
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

    public function __construct(Generator $generator, Deployer $deployer, Filesystem $filesystem)
    {
        $this->generator  = $generator;
        $this->deployer   = $deployer;
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
                'repository',
                null,
                InputOption::VALUE_REQUIRED,
                'Target repository in which to deploy the website.',
                trim(shell_exec('git config --get remote.origin.url'))
            )
            ->addOption(
                'branch',
                null,
                InputOption::VALUE_REQUIRED,
                'Target branch in which to deploy the website.',
                'gh-pages'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceDirectory = $input->getArgument('source');
        $repositoryUrl   = $input->getArgument('repository');
        $targetBranch    = $input->getOption('branch');

        $project = new Project($sourceDirectory, getcwd() . '/.couscous/generated');

        // Generate the website
        $this->generator->generate($project, $output);

        $output->writeln('');

        // Deploy it
        $this->deployer->deploy($project, $output, $repositoryUrl, $targetBranch);
    }
}
