<?php

namespace Couscous;

use DI\ContainerBuilder;
use Interop\Container\ContainerInterface;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ContainerFactory
{
    /**
     * @return ContainerInterface
     */
    public function createContainer()
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions(__DIR__ . '/config.php');

        return $builder->build();
    }
}
