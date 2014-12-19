<?php

namespace Couscous\Application\Cli;

use Couscous\Generator;
use Couscous\Model\Repository;
use Couscous\Deployer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Generate and deploy the website after successful Travis build.
 *
 * @author Gaultier Boniface <gboniface@wysow.fr>
 */
class TravisAutoDeployCommand extends Command
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
            ->setName('travis-auto-deploy')
            ->setDescription('Automatically generate and deploy the website after a successful Travis build')
            ->addArgument(
                'source',
                InputArgument::OPTIONAL,
                'Repository you want to generate.',
                getcwd()
            )
            ->addOption(
                'php-version',
                null,
                InputOption::VALUE_REQUIRED,
                'Specify for which php version you want to deploy documentation, mainly to avoid multiple deploys',
                '5.4'
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
        $repositoryUrl   = sprintf('https://%s@%s', getenv('GH_TOKEN'), getenv('GH_REF'));
        $targetBranch    = $input->getOption('branch');

        $repository = new Repository($sourceDirectory, getcwd() . '/.couscous/generated');

        // verify some env variables
        $travisBranch = getenv('TRAVIS_BRANCH');

        if ($travisBranch != 'master') {
            $output->writeln('<notice>[NOT DEPLOYED] Deploying Couscous only for master branch</notice>');
            exit(1);
        }

        $isPullRequest = (bool) getenv('TRAVIS_PULL_REQUEST');

        if ($isPullRequest) {
            $output->writeln('<notice>[NOT DEPLOYED] Not deploying Couscous for pull requests</notice>');
            exit(1);
        }

        // creating out directory
        $output->writeln('<info>Creating "out" directory</info>');
        mkdir('out');

        // set git user data
        $output->writeln('<info>Setting up git user</info>');
        shell_exec('git config user.name ${GIT_NAME}');
        shell_exec('git config user.email ${GIT_EMAIL}');

        // getting current php version to only deploy once
        $currentPhpVersion = shell_exec('phpenv global');
        if ($input->getOption('php-version') == $currentPhpVersion) {
            // Generate the website
            $this->generator->generate($repository, $output);

            $output->writeln('');
            // Deploy it
            $this->deployer->deploy($repository, $output, $repositoryUrl, $targetBranch);
            exit(1);
        }

        $output->writeln('<notice>This version of the documentation is already deployed</notice>');
        exit(1);
    }
}
