<?php

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Module\Core\Step\AddFileNameToMetadata;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Tests\UnitTest\Mock\MockProject;

/**
 * @covers \Couscous\Module\Core\Step\AddFileNameToMetadata
 */
class AddFileNameToMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_add_the_metadata_variable()
    {
        $project = new MockProject();
        $project->addFile(new HtmlFile('foo/bar/index.html', ''));

        $step = new AddFileNameToMetadata();
        $step->__invoke($project);

        $files = $project->getFiles();
        $file = current($files);

        $this->assertEquals('foo/bar/index.html', $file->getMetadata()['currentFile']);
    }
}
