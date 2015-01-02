<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Module\Config\Step\OverrideBaseUrlForPreview;
use Couscous\Tests\UnitTest\Mock\MockRepository;

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
        $repository = new MockRepository();
        $repository->metadata['baseUrl'] = 'foo';
        $repository->metadata['preview'] = true;

        $step = new OverrideBaseUrlForPreview();
        $step->__invoke($repository);

        $this->assertEquals('', $repository->metadata['baseUrl']);
    }

    /**
     * @test
     */
    public function should_not_override_baseUrl_if_not_preview()
    {
        $repository = new MockRepository();
        $repository->metadata['baseUrl'] = 'foo';
        $repository->metadata['preview'] = false;

        $step = new OverrideBaseUrlForPreview();
        $step->__invoke($repository);

        $this->assertEquals('foo', $repository->metadata['baseUrl']);
    }
}
