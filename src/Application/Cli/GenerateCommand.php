<?php

namespace Couscous\Application\Cli;

use Couscous\Generator;
use Couscous\Model\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class GenerateCommand extends Command
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

    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate the website')
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = new Project($input->getArgument('source'), $input->getOption('target'));

        $this->generator->generate($project, $output);
    }
}
