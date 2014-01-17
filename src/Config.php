<?php

namespace Couscous;

use Symfony\Component\Yaml\Yaml;

/**
 * Configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Config
{
    /**
     * List of directories to exclude.
     * @var string[]
     */
    public $exclude = array('vendor');

    /**
     * Directory in which the website template is.
     * @var string
     */
    public $directory = 'website';

    /**
     * Base URL for the templates.
     * @var string
     */
    public $baseUrl = '/';

    /**
     * Scripts to execute before generating the website.
     * @var string[]
     */
    public $before = array();

    /**
     * Scripts to execute after generating the website.
     * @var string[]
     */
    public $after = array();

    /**
     * Create the config from a YAML file.
     *
     * @param string $file If the file doesn't exist, returns a default config.
     *
     * @return Config
     */
    public static function fromYaml($file)
    {
        if (! file_exists($file)) {
            return new self();
        }

        $values = Yaml::parse(file_get_contents($file));

        $config = new self();

        if (! is_array($values)) {
            return $config;
        }

        if (array_key_exists('exclude', $values)) {
            $config->exclude = (array) $values['exclude'];
        }
        if (array_key_exists('directory', $values)) {
            $config->directory = trim($values['directory']);
        }
        if (array_key_exists('baseUrl', $values)) {
            $config->baseUrl = rtrim(trim($values['baseUrl']), '/');
        }
        if (array_key_exists('before', $values)) {
            $config->before = (array) $values['before'];
        }
        if (array_key_exists('after', $values)) {
            $config->after = (array) $values['after'];
        }

        return $config;
    }
}
