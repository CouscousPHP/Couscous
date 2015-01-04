<?php

namespace Couscous\Application;

use DI\Container;
use DI\ContainerBuilder;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ContainerFactory
{
    /**
     * @return Container
     */
    public function createContainer()
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions(__DIR__ . '/config.php');

        return $builder->build();
    }
}
