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

        $output->writeln('Generating ' . $sourceDirectory . ' to ' . $targetDirectory);

        // Create the target directory
        if (! $filesystem->exists($targetDirectory)) {
            $filesystem->mkdir($targetDirectory);
        }

        // Clear target directory
        $targetFinder = new Finder();
        $filesystem->remove($targetFinder->in($targetDirectory));

        // Copy the template files
        $output->writeln('Copying template files');
        $filesystem->mirror($templateDirectory . '/public', $targetDirectory, null, array('delete' => true));

        // Processors
        $processor = $this->getProcessor($config, $templateDirectory);

        // Process each page
        $finder = new Finder();
        $finder->files()->in($sourceDirectory)
            ->ignoreDotFiles(true)
            ->exclude(array_merge($config->exclude, array('.generated')))
            ->name('*.md');
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $output->writeln('Generating ' . $file->getRelativePathname());

            // Process the file content
            $page = new Page($file->getFilename(), $file->getContents());
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

    /**
     * @param Config $config
     * @param string $templateDirectory
     * @return Processor
     */
    private function getProcessor(Config $config, $templateDirectory)
    {
        $processor = new ProcessorChain();
        $processor->chain(new MarkdownProcessor());
        $processor->chain(new LinkProcessor());
        $loader = new Twig_Loader_Filesystem($templateDirectory);
        $twig = new Twig_Environment($loader);
        $processor->chain(new TwigProcessor($twig, $config->baseUrl));
        $processor->chain(new FileNameProcessor());

        return $processor;
    }
}
