<?php

namespace Couscous\Application\Cli;

use Humbug\SelfUpdate\Updater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Phar file self update.
 *
 * @author Gaultier Boniface <gboniface@wysow.fr>
 */
class SelfUpdateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setAliases(['selfupdate'])
            ->setDescription('Automatically update the phar file if needed.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updater = new Updater(null, false);
        $updater->getStrategy()->setPharUrl('https://couscous.io/couscous.phar');
        $updater->getStrategy()->setVersionUrl('https://couscous.io/couscous.version');

        $result = $updater->update();
        $result ? $output->writeln('Phar file updated successfully!') : $output->writeln('No need to update.');
    }
}
