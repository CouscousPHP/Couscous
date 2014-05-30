<?php

namespace Couscous;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Contains information regarding a generation.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class GenerationHelper
{
    /**
     * @var Config
     */
    public $config;

    /**
     * Directory containing the sources (Markdown files) to compile to HTML.
     * @var string
     */
    public $sourceDirectory;

    /**
     * Directory in which to generate the website.
     * @var string
     */
    public $targetDirectory;

    /**
     * Temporary directory that can be used during the generation.
     * @var string
     */
    public $tempDirectory;

    /**
     * @var OutputInterface
     */
    public $output;

    public function __construct(
        Config $config,
        $sourceDirectory,
        $targetDirectory,
        $tempDirectory,
        OutputInterface $output
    )
    {
        $this->config = $config;
        $this->sourceDirectory = (string) $sourceDirectory;
        $this->targetDirectory = (string) $targetDirectory;
        $this->tempDirectory = (string) $tempDirectory;
        $this->output = $output;
    }
}
