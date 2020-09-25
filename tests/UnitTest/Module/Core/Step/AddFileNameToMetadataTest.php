<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Core\Step;

use Couscous\Module\Core\Step\AddFileNameToMetadata;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Module\Core\Step\AddFileNameToMetadata
 */
class AddFileNameToMetadataTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_add_the_metadata_variable(): void
    {
        $project = new MockProject();
        $project->addFile(new HtmlFile('foo/bar/index.html', ''));

        $step = new AddFileNameToMetadata();
        $step->__invoke($project);

        $files = $project->getFiles();
        $file = current($files);

        self::assertEquals('foo/bar/index.html', $file->getMetadata()['currentFile']);
    }
}
