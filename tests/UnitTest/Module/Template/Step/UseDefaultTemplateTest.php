<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Module\Template\Step\UseDefaultTemplate;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Module\Template\Step\UseDefaultTemplate
 */
class UseDefaultTemplateTest extends TestCase
{
    /**
     * @test
     */
    public function use_default_template_when_no_template_configured(): void
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $project = new MockProject();
        $step->__invoke($project);

        self::assertEquals(UseDefaultTemplate::DEFAULT_TEMPLATE_URL, $project->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_directory(): void
    {
        $step = new UseDefaultTemplate($this->createFilesystem(true));

        $project = new MockProject();
        $step->__invoke($project);

        self::assertNull($project->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_directory_set(): void
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $project = new MockProject();
        $project->metadata['template.directory'] = 'foo';

        $step->__invoke($project);

        self::assertNull($project->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_url_set(): void
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $project = new MockProject();
        $project->metadata['template.url'] = 'foo';

        $step->__invoke($project);

        // Assert URL isn't overridden
        self::assertEquals('foo', $project->metadata['template.url']);
    }

    /**
     * @return MockObject&Filesystem
     */
    private function createFilesystem(bool $return = false)
    {
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem
            ->method('exists')
            ->willReturn($return);

        return $filesystem;
    }
}
