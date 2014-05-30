<?php

namespace Couscous;

use Couscous\Processor\FileNameProcessor;
use Couscous\Processor\LinkProcessor;
use Couscous\Processor\MarkdownProcessor;
use Couscous\Processor\Processor;
use Couscous\Processor\ProcessorChain;
use Couscous\Processor\TwigProcessor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Generates the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Generator
{
    /**
     * @param Config          $config
     * @param string          $sourceDirectory Directory containing the source files.
     * @param string          $targetDirectory Directory in which to write the generated website.
     * @param OutputInterface $output
     *
     * @throws \InvalidArgumentException
     */
    public function generate(Config $config, $sourceDirectory, $targetDirectory, OutputInterface $output)
    {
        $filesystem = new Filesystem();

        $templateDirectory = $sourceDirectory . '/' . $config->directory;
        if (! $filesystem->exists($templateDirectory)) {
            throw new \InvalidArgumentException("The template directory doesn't exist: $templateDirectory");
        }

        $output->writeln("<comment>Generating $sourceDirectory to $targetDirectory</comment>");

        // Create the target directory
        if (! $filesystem->exists($targetDirectory)) {
            $filesystem->mkdir($targetDirectory);
        }

        // Clear target directory
        $targetFinder = new Finder();
        $filesystem->remove($targetFinder->in($targetDirectory));

        // Execute the "before" scripts
        $this->execScripts($config->before, $sourceDirectory, $output);

        // Copy the template files
        $this->processTemplate($templateDirectory, $targetDirectory, $output, $filesystem);

        // Process each page
        $this->processPages($config, $sourceDirectory, $templateDirectory, $targetDirectory, $output, $filesystem);

        // Execute the "after" scripts
        $this->execScripts($config->after, $sourceDirectory, $output);
    }

    private function processTemplate($templateDirectory, $targetDirectory, OutputInterface $output, Filesystem $filesystem)
    {
        $output->writeln('Copying template files');
        $filesystem->mirror($templateDirectory . '/public', $targetDirectory, null, array('delete' => true));
    }

    private function processPages(
        Config $config,
        $sourceDirectory,
        $templateDirectory,
        $targetDirectory,
        OutputInterface $output,
        Filesystem $filesystem
    ) {
        $processor = $this->getProcessor($templateDirectory);

        $finder = new Finder();
        $finder->files()->in($sourceDirectory)
            ->ignoreDotFiles(true)
            ->exclude(array_merge($config->exclude, array('.generated')))
            ->name('*.md');

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $output->writeln('Processing ' . $file->getRelativePathname());

            // Process the file content
            $page = new Page($file->getFilename(), $file->getContents(), $config->templateVariables);
            $processor->process($page);

            // If the file doesn't already exist, we convert from markdown to HTML
            $targetFile = $targetDirectory . '/' . $file->getRelativePath() . '/' . $page->filename;
            if ($filesystem->exists($targetFile)) {
                $output->writeln('Skipping ' . $file->getRelativePathname() . ' because a file with the same name'
                    . ' already exists');
                continue;
            }

            $filesystem->dumpFile($targetFile, $page->content);
        }
    }

    private function execScripts(array $scripts, $sourceDirectory, OutputInterface $output)
    {
        foreach ($scripts as $script) {
            $script = 'cd "' . $sourceDirectory . '" && ' . $script;

            $output->writeln("Executing <info>$script</info>");

            $scriptOutput = array();
            $returnValue = 0;
            exec($script, $scriptOutput, $returnValue);

            if ($returnValue !== 0) {
                throw new \RuntimeException(
                    "Error while running '$script':" . PHP_EOL . implode(PHP_EOL, $scriptOutput)
                );
            }
        }
    }

    /**
     * @param string $templateDirectory
     * @return Processor
     */
    private function getProcessor($templateDirectory)
    {
        $processor = new ProcessorChain();
        $processor->chain(new MarkdownProcessor());
        $processor->chain(new LinkProcessor());
        $loader = new Twig_Loader_Filesystem($templateDirectory);
        $twig = new Twig_Environment($loader);
        $processor->chain(new TwigProcessor($twig));
        $processor->chain(new FileNameProcessor());

        return $processor;
    }
}
