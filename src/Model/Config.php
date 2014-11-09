<?php

namespace Couscous\Model;

use Symfony\Component\Yaml\Yaml;

/**
 * Configuration.
 *
 * Extends stdClass so that any property (i.e. config value) can be set.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Config extends \stdClass
{
    /**
     * File from which the configuration was read.
     * TODO remove (useless)
     * @var string
     */
    private $configFile;

    /**
     * List of directories to exclude.
     * @var string[]
     */
    public $exclude = array('vendor', 'website');

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
     * Create the config from a YAML file.
     *
     * @param string $file If the file doesn't exist, returns a default config.
     *
     * @todo rename to "fromArray" and move the YAML parsing into "LoadConfig"
     *
     * @return Config
     */
    public static function fromYaml($file)
    {
        $config = new self();
        $config->configFile = $file;

        if (! file_exists($file)) {
            // TODO throw exception
            return $config;
        }

        $values = Yaml::parse(file_get_contents($file));
        if (! is_array($values)) {
            // TODO throw exception
            return $config;
        }

        // Validate some config values
        // TODO move to private method
        if (array_key_exists('exclude', $values)) {
            $values['exclude'] = (array) $values['exclude'];
        }
        if (array_key_exists('directory', $values)) {
            $values['directory'] = trim($values['directory']);
        }
        if (array_key_exists('before', $values)) {
            $values['before'] = (array) $values['before'];
        }
        if (array_key_exists('after', $values)) {
            $values['after'] = (array) $values['after'];
        }
        if (array_key_exists('templateUrl', $values)) {
            $values['templateUrl'] = (string) $values['templateUrl'];
        }
        if (array_key_exists('baseUrl', $values)) {
            // Trim any trailing "/" in the base url
            $values['baseUrl'] = rtrim(trim($values['baseUrl']), '/');
        }

        // Set the properties
        foreach ($values as $key => $value) {
            $config->$key = $value;
        }

        return $config;
    }

    /**
     * Reload the configuration from disk.
     *
     * @todo remove (unused)
     *
     * @return Config
     */
    public function reload()
    {
        return self::fromYaml($this->configFile);
    }

    /**
     * Returns the config values in an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this;
    }
}
