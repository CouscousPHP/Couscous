<?php
declare(strict_types = 1);

namespace Couscous\Application;

use DI\Container;
use DI\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ContainerFactory
{
    public function createContainer(): Container
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions(__DIR__.'/config.php');

        $moduleConfigs = new Finder();
        $moduleConfigs->files()
            ->in(__DIR__.'/../Module')
            ->path('/.+/')
            ->name('config.php');

        /** @var SplFileInfo $moduleConfig */
        foreach ($moduleConfigs as $moduleConfig) {
            $builder->addDefinitions($moduleConfig->getPathname());
        }

        return $builder->build();
    }
}
