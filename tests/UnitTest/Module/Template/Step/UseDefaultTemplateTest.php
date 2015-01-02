<?php

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Module\Template\Step\UseDefaultTemplate;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Couscous\Module\Template\Step\UseDefaultTemplate
 */
class UseDefaultTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function use_default_template_when_no_template_configured()
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $repository = new MockRepository();
        $step->__invoke($repository);

        $this->assertEquals(UseDefaultTemplate::DEFAULT_TEMPLATE_URL, $repository->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_directory()
    {
        $step = new UseDefaultTemplate($this->createFilesystem(true));

        $repository = new MockRepository();
        $step->__invoke($repository);

        $this->assertNull($repository->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_directory_set()
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $repository = new MockRepository();
        $repository->metadata['template.directory'] = 'foo';

        $step->__invoke($repository);

        $this->assertNull($repository->metadata['template.url']);
    }

    /**
     * @test
     */
    public function dont_use_default_template_when_template_url_set()
    {
        $step = new UseDefaultTemplate($this->createFilesystem());

        $repository = new MockRepository();
        $repository->metadata['template.url'] = 'foo';

        $step->__invoke($repository);

        // Assert URL isn't overridden
        $this->assertEquals('foo', $repository->metadata['template.url']);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    private function createFilesystem($return = false)
    {
        $filesystem = $this->getMock('Symfony\Component\Filesystem\Filesystem');
        $filesystem->expects($this->any())
            ->method('exists')
            ->willReturn($return);
        return $filesystem;
    }
}
