<?php
declare(strict_types = 1);

namespace Couscous\Application\Cli;

use Humbug\SelfUpdate\Strategy\ShaStrategy;
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
    protected function configure(): void
    {
        $this
            ->setName('self-update')
            ->setAliases(['selfupdate'])
            ->setDescription('Automatically update the phar file if needed.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $updater = new Updater(null, false);
        /** @var ShaStrategy We code against the default strategy created by the Updater */
        $strategy = $updater->getStrategy();
        $strategy->setPharUrl('https://couscous.io/couscous.phar');
        $strategy->setVersionUrl('https://couscous.io/couscous.version');

        $result = $updater->update();
        $result ? $output->writeln('Phar file updated successfully!') : $output->writeln('No need to update.');

        return 0;
    }
}
