<?php

namespace Couscous\Step\Config;

use Couscous\Model\RepositoryMetadata;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/**
 * Loads the Couscous config for the repository.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LoadConfig implements StepInterface
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

    public function __construct(Filesystem $filesystem, Parser $yamlParser)
    {
        $this->filesystem = $filesystem;
        $this->yamlParser = $yamlParser;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $filename = $repository->sourceDirectory . '/' . self::FILENAME;

        if (! $this->filesystem->exists($filename)) {
            $output->writeln("<comment>No couscous.yml configuration file found, using default config</comment>");

            // Default empty config
            $repository->metadata = new RepositoryMetadata();
            return;
        }

        $metadata = $this->parseYamlFile($filename);

        $repository->metadata = RepositoryMetadata::fromArray($metadata);

        $repository->watchlist->watchFile($filename);
    }

    private function parseYamlFile($filename)
    {
        try {
            $metadata = $this->yamlParser->parse(file_get_contents($filename));
        } catch (ParseException $e) {
            throw InvalidConfigException::invalidYaml(self::FILENAME, $e);
        }

        if (! is_array($metadata)) {
            return array();
        }

        return $metadata;
    }
}
