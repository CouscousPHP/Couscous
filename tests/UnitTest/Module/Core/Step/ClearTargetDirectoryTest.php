<?php

namespace Couscous\Tests\UnitTest\Module\Core\Step;

use Couscous\Model\Project;
use Couscous\Module\Core\Step\ClearTargetDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @covers \Couscous\Model\Project
 */
class ClearTargetDirectoryTest extends TestCase
{
    /**
     * Dot files like .travis.yaml or .github/workflows/action.yml should not be removed.
     * @test
     */
    public function it_should_not_clear_dot_files()
    {
        $project = new Project('foo', dirname(__DIR__, 3) .'/Fixture/directory-with-dot-files');

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['remove'])
            ->getMock();

        $filesystem
            ->expects($this->once())
            ->method('remove')
            ->with($this->callback(function (Finder $files) {
                foreach($files as $file) {
                    if (in_array($file->getFilename(), ['.gitkeep'. 'foobar.txt', '.github'])) {
                        return false;
                    }
                }

                return true;
            }));

        $step = new ClearTargetDirectory($filesystem);
        $step->__invoke($project);
    }
}
