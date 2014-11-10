<?php

namespace Couscous\Model;

/**
 * Repository metadata.
 *
 * Extends stdClass so that any custom value can be set.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RepositoryMetadata extends \stdClass
{
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
     * @param array $values
     *
     * @return RepositoryMetadata
     */
    public static function fromArray(array $values)
    {
        $metadata = new self();

        // Validate some values
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

        foreach ($values as $key => $value) {
            $metadata->$key = $value;
        }

        return $metadata;
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
