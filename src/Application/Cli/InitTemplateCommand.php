<?php
declare(strict_types = 1);

namespace Couscous\Application\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initialize a new Couscous template.
 *
 * @author Ross J. Hagan <rossjhagan@gmail.com>
 */
class InitTemplateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('init:template')
            ->setDescription('Initialize a new Couscous template');

        $this->addArgument(
            'template_name',
            InputArgument::REQUIRED,
            'Template name'
        )
        ->addArgument(
            'directory',
            InputArgument::OPTIONAL,
            'Directory name',
            'website'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileExtension = '.twig';

        /** @var string */
        $dirName = $input->getArgument('directory');
        $directory = getcwd().'/'.$dirName.'/';
        /** @var string */
        $templateName = $input->getArgument('template_name');
        $templateName = $templateName.$fileExtension;

        $fileLocation = $directory.$templateName;
        $fileExists = file_exists($fileLocation);

        if (!file_exists(getcwd().'/'.$dirName)) {
            $output->writeln('<comment>Creating directory.</comment>');
            mkdir(getcwd().'/'.$dirName);
        }

        if ($fileExists) {
            $output->writeln('<error>That template exists at '.$fileLocation.', so nothing has been changed.</error>');
            $output->writeln('<error>Try another name!</error>');

            return 1;
        }

        $output->writeln('<comment>Initialising template.</comment>');
        $template = <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <title>My project!</title>
    </head>
    <body>

        {% block content %}

        <p>
            Don't forget you can add variables into your YAML front matter, or in your couscous.yml
            then use them inside double curly braces!
        </p>

        <p>
            Also, set up a baseUrl in your couscous.yml and use it
            to <a href="{{ baseUrl }}/link/in/the/site">link to another page</a> in your site
        </p>

            {{ content|raw }}

        {% endblock %}

    </body>
</html>
HTML;
        file_put_contents($fileLocation, $template);

        return 0;
    }
}
