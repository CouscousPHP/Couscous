<?php

namespace Couscous;

use Couscous\Processor\Markdown\MarkdownFileNameProcessor;
use Couscous\Processor\Markdown\MarkdownLinkProcessor;
use Couscous\Processor\Markdown\MarkdownProcessor;
use Couscous\Processor\Processor;
use Couscous\Processor\ProcessorChain;
use Couscous\Processor\TwigProcessor;
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
     * @param GenerationHelper $generation
     *
     * @throws \InvalidArgumentException
     */
    public function generate(GenerationHelper $generation)
    {
        $filesystem = new Filesystem();

        $generation->output->writeln(sprintf(
            "<comment>Generating %s to %s</comment>",
            $generation->sourceDirectory,
            $generation->targetDirectory
        ));

        // Create the target directory
        if (! $filesystem->exists($generation->targetDirectory)) {
            $filesystem->mkdir($generation->targetDirectory);
        }

        // Clear target directory
        $targetFinder = new Finder();
        $filesystem->remove($targetFinder->in($generation->targetDirectory));

        // Execute the "before" scripts
        $this->execScripts($generation, $generation->config->before);

        // Copy the template files
        $templateDirectory = $this->processTemplate($generation, $filesystem);

        // Process each page
        $this->processPages($generation, $templateDirectory, $filesystem);

        // Execute the "after" scripts
        $this->execScripts($generation, $generation->config->after);
    }

    private function processTemplate(GenerationHelper $generation, Filesystem $filesystem)
    {
        $templateUrl = $generation->config->templateUrl;

        if ($templateUrl !== null) {
            // Template is in a git repo
            $generation->output->writeln("Fetching template from <info>$templateUrl</info>");

            $templateDirectory = $generation->tempDirectory . '/template';
            if (file_exists($templateDirectory)) {
                $command = "cd $templateDirectory && git pull 2>&1";
            } else {
                $command = "git clone $templateUrl $templateDirectory 2>&1";
            }
            $gitOutput = array();
            exec($command, $gitOutput, $returnValue);
            if ($returnValue !== 0) {
                throw new \RuntimeException(implode(PHP_EOL, $gitOutput));
            }
        } else {
            // Template is in a directory
            $templateDirectory = $generation->sourceDirectory . '/' . $generation->config->directory;

            if (! $filesystem->exists($templateDirectory)) {
                throw new \InvalidArgumentException("The template directory doesn't exist: $templateDirectory");
            }
        }

        $generation->output->writeln('Copying template files');
        $filesystem->mirror($templateDirectory . '/public', $generation->targetDirectory, null, array('delete' => true));

        return $templateDirectory;
    }

    private function processPages(GenerationHelper $generation, $templateDirectory, Filesystem $filesystem)
    {
        $processor = $this->getProcessor($templateDirectory);

        $finder = new Finder();
        $finder->files()->in($generation->sourceDirectory)
            ->ignoreDotFiles(true)
            ->exclude(array_merge($generation->config->exclude, array('.generated')))
            ->name('*.md');

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $generation->output->writeln('Processing ' . $file->getRelativePathname());

            // Process the file content
            $page = new Page($file->getFilename(), $file->getContents(), $generation->config->templateVariables);
            $processor->process($page);

            $targetFile = $generation->targetDirectory . '/' . $file->getRelativePath() . '/' . $page->filename;
            if ($filesystem->exists($targetFile)) {
                $generation->output->writeln('Skipping ' . $file->getRelativePathname()
                    . ' because a file with the same name already exists');
                continue;
            }

            $filesystem->dumpFile($targetFile, $page->content);
        }
    }

    private function execScripts(GenerationHelper $generation, array $scripts)
    {
        foreach ($scripts as $script) {
            $script = 'cd "' . $generation->sourceDirectory . '" && ' . $script;

            $generation->output->writeln("Executing <info>$script</info>");

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
        $processor->chain(new MarkdownLinkProcessor());
        $loader = new Twig_Loader_Filesystem($templateDirectory);
        $twig = new Twig_Environment($loader, array(
            'cache' => false,
            'auto_reload' => true,
        ));
        $processor->chain(new TwigProcessor($twig));
        $processor->chain(new MarkdownFileNameProcessor());

        return $processor;
    }
}
