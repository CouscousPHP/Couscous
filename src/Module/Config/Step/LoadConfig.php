<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/**
 * Loads the Couscous config for the project.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LoadConfig implements Step
{
    const FILENAME = 'couscous.yml';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Parser
     */
    private $yamlParser;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Filesystem $filesystem, Parser $yamlParser, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->yamlParser = $yamlParser;
        $this->logger = $logger;
    }

    public function __invoke(Project $project)
    {
        $filename = $project->sourceDirectory.'/'.self::FILENAME;

        if (!$this->filesystem->exists($filename)) {
            $this->logger->notice('No couscous.yml configuration file found, using default config');

            return;
        }

        $metadata = $this->parseYamlFile($filename);
        $metadata = $this->validateConfig($metadata);

        $project->metadata->setMany($metadata);
        $project->watchlist->watchFile($filename);
    }

    private function parseYamlFile($filename)
    {
        try {
            $metadata = $this->yamlParser->parse(file_get_contents($filename));
        } catch (ParseException $e) {
            throw InvalidConfigException::invalidYaml(self::FILENAME, $e);
        }

        if (!is_array($metadata)) {
            return [];
        }

        return $metadata;
    }

    private function validateConfig($values)
    {
        if (array_key_exists('include', $values)) {
            $values['include'] = (array) $values['include'];
        }
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

        return $values;
    }
}
