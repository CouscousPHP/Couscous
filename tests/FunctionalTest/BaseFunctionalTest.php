<?php

namespace Piwik\Tests\FunctionalTest;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class BaseFunctionalTest extends \PHPUnit_Framework_TestCase
{
    protected $generatedDirectory;

    public function setUp()
    {
        parent::setUp();

        $this->generatedDirectory = __DIR__ . '/generated';
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->clearGeneratedDirectory();
    }

    public function assertGeneratedWebsite($fixtureName)
    {
        list($output, $return) = $this->generate($fixtureName);

        $this->assertSame(0, $return, implode(PHP_EOL, $output));

        $fixtureDir = __DIR__ . '/fixtures/' . $fixtureName;

        $finder = new Finder();
        $finder->files()->in($fixtureDir . '/expected');
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $generatedFile = $this->generatedDirectory . '/' . $file->getRelativePathname();
            $this->assertFileExists($generatedFile, 'The file was not generated');
            $this->assertFileEquals(
                $file->getPathname(),
                $generatedFile,
                sprintf("The generated file doesn't equals the expected file: %s", $file->getRelativePathname())
            );
        }
    }

    public function assertGenerationError($fixtureName, $expectedMessage)
    {
        list($output, $return) = $this->generate($fixtureName);

        $this->assertNotEquals(0, $return, 'Failed asserting that the generation failed');
        $this->assertContains($expectedMessage, implode(PHP_EOL, $output));
    }

    private function createCommand($fixtureName)
    {
        $bin = realpath(__DIR__ . '/../../bin/couscous');
        $fixtureName = __DIR__ . '/fixtures/' . $fixtureName . '/source';
        $targetDirectory = __DIR__ . '/generated';

        return sprintf(
            '%s generate -v --target="%s" %s 2>&1',
            $bin,
            $targetDirectory,
            $fixtureName
        );
    }

    private function generate($fixtureName)
    {
        $this->clearGeneratedDirectory();

        $command = $this->createCommand($fixtureName);

        exec($command, $output, $return);

        return array($output, $return);
    }

    private function clearGeneratedDirectory()
    {
        $fs = new Filesystem();
        $fs->remove($this->generatedDirectory);
    }
}
