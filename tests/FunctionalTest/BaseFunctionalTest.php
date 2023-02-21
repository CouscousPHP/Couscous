<?php

namespace Couscous\Tests\FunctionalTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class BaseFunctionalTest extends TestCase
{
    protected $generatedDirectory;

    public function setUp(): void
    {
        parent::setUp();

        $this->generatedDirectory = __DIR__.'/generated';
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->clearGeneratedDirectory();
    }

    public function assertGeneratedWebsite($fixtureName, $configFile = null)
    {
        [$output, $return] = $this->generate($fixtureName, $configFile);

        $this->assertSame(0, $return, implode(PHP_EOL, $output));

        $fixtureDir = __DIR__.'/Fixture/'.$fixtureName;
        $expectedDir = $fixtureDir.'/expected';

        // Check against expected files
        $finder = new Finder();
        $finder->files()->in($expectedDir);
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $generatedFile = $this->generatedDirectory.'/'.$file->getRelativePathname();
            $this->assertFileExists($generatedFile, sprintf('%s was not generated', $file->getRelativePathname()));
            $this->assertEquals(
                trim(file_get_contents($file->getPathname())),
                trim(file_get_contents($generatedFile)),
                sprintf("The generated file doesn't equals the expected file: %s", $file->getRelativePathname())
            );
        }

        // Check that there is no additional file generated that wasn't expected
        $finder = new Finder();
        $finder->files()->in($this->generatedDirectory);
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $expectedFile = $expectedDir.'/'.$file->getRelativePathname();
            $message = sprintf(
                "The file %s was generated but wasn't expected",
                $file->getRelativePathname()
            );
            $this->assertFileExists($expectedFile, $message);
        }
    }

    public function assertGenerationError($fixtureName, $expectedMessage)
    {
        [$output, $return] = $this->generate($fixtureName);
        $output = implode(PHP_EOL, $output);

        $this->assertNotEquals(0, $return, 'Failed asserting that the generation failed: '.$output);
        $this->assertStringContainsString($expectedMessage, $output);
    }

    private function createCommand($fixtureName, $configFile = null): string
    {
        $bin = realpath(__DIR__.'/../../bin/couscous');
        $fixtureName = __DIR__.'/Fixture/'.$fixtureName.'/source';
        $targetDirectory = __DIR__.'/generated';
        $configOption = ($configFile !== null) ? "--config-file=$configFile" : '';

        return sprintf(
            '%s generate -v --target="%s" %s %s 2>&1',
            $bin,
            $targetDirectory,
            $configOption,
            $fixtureName
        );
    }

    private function generate($fixtureName, $configFile = null): array
    {
        $this->clearGeneratedDirectory();

        $command = $this->createCommand($fixtureName, $configFile);

        exec($command, $output, $return);

        return [$output, $return];
    }

    private function clearGeneratedDirectory()
    {
        $fs = new Filesystem();
        $fs->remove($this->generatedDirectory);
    }
}
