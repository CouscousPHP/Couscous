<?php

namespace Couscous\Tests\UnitTest\Module\Config\Step;

use Couscous\Model\Project;
use Couscous\Module\Config\Step\LoadConfig;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser;

/**
 * @covers \Couscous\Module\Config\Step\LoadConfig
 */
class LoadConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var Filesystem */
    protected $filesystem;
    /** @var string */
    protected $workingDir;

    protected function setUp()
    {
        $this->filesystem = new Filesystem();
        $this->workingDir = $this->getUniqueTmpDirectory();
    }

    protected function tearDown()
    {
        if ($this->filesystem->exists($this->workingDir)) {
            $this->filesystem->remove($this->workingDir);
        }
    }

    /**
     * @test
     */
    public function it_should_load_simple_config()
    {
        // Copy fixtures to working directory.
        $this->filesystem->copy($this->getFixturePath('couscous-simple.yml'), $this->workingDir.DIRECTORY_SEPARATOR.LoadConfig::FILENAME);

        // Create a project for working directory.
        $project = new Project($this->workingDir, '');

        // Load config.
        $parser = new Parser();
        $step = new LoadConfig($this->filesystem, $parser, new NullLogger());
        $step->__invoke($project);

        $this->assertEquals(['some/dir', 'some/other/dir'], $project->metadata['include']);
        $this->assertEquals('coucous-simple!', $project->metadata['title']);
    }

    /**
     * @test
     */
    public function it_should_overwrite_config()
    {
        // Copy fixtures to working directory.
        $this->filesystem->copy($this->getFixturePath('couscous-with-import.yml'), $this->workingDir.DIRECTORY_SEPARATOR.LoadConfig::FILENAME);
        $this->filesystem->copy($this->getFixturePath('imported-file1.yml'), $this->workingDir.DIRECTORY_SEPARATOR.'imported-file1.yml');

        // Create a project for working directory.
        $project = new Project($this->workingDir, '');

        // Load config.
        $parser = new Parser();
        $step = new LoadConfig($this->filesystem, $parser, new NullLogger());
        $step->__invoke($project);

        $this->assertEquals(['some/dir', 'some/other/dir', 'other/dir/imported'], $project->metadata['include']);
        $this->assertEquals('overwritten by imported-file1.yml', $project->metadata['title']);
    }

    /**
     * Locate fixtures and return path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getFixturePath($name)
    {
        return __DIR__.'/Fixtures/'.$name;
    }

    /**
     * Create a unique working directory within temp dir.
     *
     * Shamelessly borrowed from Composer\TestCase.
     *
     * @return string
     */
    public function getUniqueTmpDirectory()
    {
        $attempts = 5;
        $root = sys_get_temp_dir();

        do {
            $unique = $root.DIRECTORY_SEPARATOR.uniqid('couscous-test-'.rand(1000, 9000));

            if (!$this->filesystem->exists($unique)) {
                // Create and return.
                $this->filesystem->mkdir($unique, 0777);

                return realpath($unique);
            }
        } while (--$attempts);

        throw new \RuntimeException('Failed to create a unique temporary directory.');
    }
}
