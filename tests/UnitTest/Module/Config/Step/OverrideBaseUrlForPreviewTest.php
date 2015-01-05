<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\OverrideBaseUrlForPreview;
use Couscous\Tests\UnitTest\Mock\MockProject;

/**
 * @covers \Couscous\Module\Config\Step\OverrideBaseUrlForPreview
 */
class OverrideBaseUrlForPreviewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function should_override_baseUrl_if_preview()
    {
        $project = new MockProject();
        $project->metadata['baseUrl'] = 'foo';
        $project->metadata['preview'] = true;

        $step = new OverrideBaseUrlForPreview();
        $step->__invoke($project);

        $this->assertEquals('', $project->metadata['baseUrl']);
    }

    /**
     * @test
     */
    public function should_not_override_baseUrl_if_not_preview()
    {
        $project = new MockProject();
        $project->metadata['baseUrl'] = 'foo';
        $project->metadata['preview'] = false;

        $step = new OverrideBaseUrlForPreview();
        $step->__invoke($project);

        $this->assertEquals('foo', $project->metadata['baseUrl']);
    }
}
