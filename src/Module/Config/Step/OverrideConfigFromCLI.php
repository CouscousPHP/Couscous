<?php
declare(strict_types = 1);

namespace Couscous\Module\Config\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Psr\Log\LoggerInterface;

/**
 * Override config variables when specified using --config option.
 *
 * @author D.J. Marcolesco <dj.marcolesco@gmail.com>
 */
class OverrideConfigFromCLI implements Step
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Project $project): void
    {
        if (!$project->metadata['cliConfig']) {
            return;
        }

        $cliConfig = [];
        /** @var string $item */
        foreach ($project->metadata['cliConfig'] as $item) {
            $explosion = explode('=', $item, 2);
            $this->logger->notice('Overriding global config: '.$explosion[0].' = "'.$explosion[1].'"');

            $cliConfig[$explosion[0]] = $explosion[1];
        }

        unset($project->metadata['cliConfig']);

        $project->metadata->setMany($cliConfig);
    }
}
