<?php

namespace Couscous;

use GitWrapper\GitWrapper;
use Symfony\Component\Console\Output\OutputInterface;

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
        exec("git checkout -b $targetBranch origin/$targetBranch 2>&1", $gitOutput, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException("Does the branch '$targetBranch' exist?" . PHP_EOL . implode(PHP_EOL, $gitOutput));
        }
    }
}
