<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\OverrideConfigFromCLI;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Couscous\Module\Config\Step\OverrideConfigFromCLI
 */
class OverrideConfigFromCLITest extends TestCase
{
    /**
     * @test
     */
    public function should_override_title_if_specified(): void
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';
        $project->metadata['cliConfig'] = ['title=bar'];

        $logger = $this->createMock(LoggerInterface::class);

        $step = new OverrideConfigFromCLI($logger);
        $step->__invoke($project);

        self::assertEquals('bar', $project->metadata['title']);
    }

    /**
     * @test
     */
    public function should_not_override_title_if_not_specified(): void
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';
        $project->metadata['cliConfig'] = [];

        $logger = $this->createMock(LoggerInterface::class);

        $step = new OverrideConfigFromCLI($logger);
        $step->__invoke($project);

        self::assertEquals('foo', $project->metadata['title']);
    }

    /**
     * @test
     */
    public function should_not_override_title_if_no_cliConfig(): void
    {
        $project = new MockProject();
        $project->metadata['title'] = 'foo';

        $logger = $this->createMock(LoggerInterface::class);

        $step = new OverrideConfigFromCLI($logger);
        $step->__invoke($project);

        self::assertEquals('foo', $project->metadata['title']);
    }
}
