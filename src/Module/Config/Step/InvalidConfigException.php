<?php

namespace Couscous\Module\Config\Step;

/**
 * Invalid configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InvalidConfigException extends \Exception
{
    public static function invalidYaml($file, \Exception $previous = null)
    {
        return new self(sprintf(
            'The YAML configuration file %s contains invalid YAML%s',
            $file,
            $previous ? ': '.$previous->getMessage() : ''
        ), 0, $previous);
    }
}
