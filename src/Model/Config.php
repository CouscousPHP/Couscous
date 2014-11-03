<?php

namespace Couscous\Model;

use Symfony\Component\Yaml\Yaml;

/**
 * Configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Config
{
    /**
     * File from which the configuration was read.
     * @var string
     */
    private $configFile;

    /**
     * List of directories to exclude.
     * @var string[]
     */
    public $exclude = array('vendor');

    /**
     * Directory in which the website template is.
     * @var string
     */
    public $directory;

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
     * URL of the template.
     * @var string
     */
    public $templateUrl;

    /**
     * Variables made available in layouts.
     * @var array
     */
    public $templateVariables = array();

    /**
     * Create the config from a YAML file.
     *
     * @param string $file If the file doesn't exist, returns a default config.
     *
     * @return Config
     */
    public static function fromYaml($file)
    {
        $config = new self();
        $config->configFile = $file;

        if (! file_exists($file)) {
            return $config;
        }

        $values = Yaml::parse(file_get_contents($file));
        if (! is_array($values)) {
            return $config;
        }

        if (array_key_exists('exclude', $values)) {
            $config->exclude = (array) $values['exclude'];
        }
        if (array_key_exists('directory', $values)) {
            $config->directory = trim($values['directory']);
        }
        if (array_key_exists('before', $values)) {
            $config->before = (array) $values['before'];
        }
        if (array_key_exists('after', $values)) {
            $config->after = (array) $values['after'];
        }
        if (array_key_exists('templateUrl', $values)) {
            $config->templateUrl = (string) $values['templateUrl'];
        }
        if (array_key_exists('template', $values)) {
            $config->templateVariables = (array) $values['template'];
            // Trim any trailing "/" in the base url
            if (array_key_exists('baseUrl', $config->templateVariables)) {
                $config->templateVariables['baseUrl'] = rtrim(trim($config->templateVariables['baseUrl']), '/');
            }
        }

        return $config;
    }

    /**
     * Reload the configuration from disk.
     *
     * @return Config
     */
    public function reload()
    {
        return self::fromYaml($this->configFile);
    }
}
