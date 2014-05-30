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
     * @var Config
     */
    public $config;

    /**
     * @var OutputInterface
     */
    public $output;

    public function __construct(Config $config, OutputInterface $output)
    {
        $this->config = $config;
        $this->output = $output;
    }
}
