<?php

namespace Couscous;

use GitWrapper\GitWrapper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Publish a website on a git branch.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Publisher
{
    /**
     * Publish the content of the given directory to the given branch.
     *
     * @param string          $directory     Directory containing the files to publish in the branch.
     * @param string          $repositoryUrl Repository in which to publish the files.
     * @param string          $targetBranch  Git branch in which to publish the files.
     * @param string          $tempDirectory Temp directory that can be used for the operation.
     * @param OutputInterface $output
     * @throws \RuntimeException
     */
    public function publish($directory, $repositoryUrl, $targetBranch, $tempDirectory, OutputInterface $output)
    {
        $output->writeln("<comment>Publishing the website</comment>");

        $filesystem = new Filesystem();

        // Clone
        $output->writeln("Cloning <info>$repositoryUrl</info> in <info>$tempDirectory</info>");
        $gitOutput = array();
        exec("git clone $repositoryUrl $tempDirectory 2>&1", $gitOutput, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException(implode(PHP_EOL, $gitOutput));
        }

        // Checkout branch
        $output->writeln("Checking out branch <info>$targetBranch</info>");
        $gitOutput = array();
        exec("cd '$tempDirectory' && git checkout -b $targetBranch origin/$targetBranch 2>&1", $gitOutput, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException("Does the branch '$targetBranch' exist?" . PHP_EOL . implode(PHP_EOL, $gitOutput));
        }

        // Copy files
        $output->writeln("Copying generated website");
        // Clear existing files
        $finder = new Finder();
        $finder->files()->in($tempDirectory)
            ->ignoreVCS(true);
        $filesystem->remove($finder);
        // Copy files
        $filesystem->mirror($directory, $tempDirectory);

        // Commit changes
        $output->writeln("Committing changes");
        $gitOutput = array();
        $message = "Website generation with Couscous";
        exec("cd '$tempDirectory' && git add --all . && git commit -m '$message'", $gitOutput, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException(implode(PHP_EOL, $gitOutput));
        }

        // Push
        $output->writeln("Pushing <info>$targetBranch</info> (GitHub may ask you to login)");
        $gitOutput = array();
        exec("cd '$tempDirectory' && git push origin $targetBranch", $gitOutput, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException(implode(PHP_EOL, $gitOutput));
        }
    }
}
