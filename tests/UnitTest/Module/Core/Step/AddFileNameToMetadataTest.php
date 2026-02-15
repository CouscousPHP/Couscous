<?php

namespace Couscous\Tests\UnitTest\Module\Core\Step;

use Couscous\Module\Core\Step\AddFileNameToMetadata;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AddFileNameToMetadata::class)]
class AddFileNameToMetadataTest extends TestCase
{
    #[Test]
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
