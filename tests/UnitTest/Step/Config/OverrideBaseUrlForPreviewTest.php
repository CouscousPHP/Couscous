<?php

namespace Couscous\Tests\UnitTest\Step\Config;

use Couscous\Step\Config\OverrideBaseUrlForPreview;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Couscous\Step\Config\OverrideBaseUrlForPreview
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
        $step->__invoke($repository, new NullOutput());

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
        $step->__invoke($repository, new NullOutput());

        $this->assertEquals('foo', $repository->metadata['baseUrl']);
    }
}
