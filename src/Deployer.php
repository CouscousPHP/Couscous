<?php

namespace Couscous;

use Couscous\CommandRunner\CommandException;
use Couscous\CommandRunner\CommandRunner;
use Couscous\Model\Project;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Deploy the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Deployer
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var CommandRunner
     */
    private $commandRunner;

    public function __construct(Filesystem $filesystem, CommandRunner $commandRunner)
    {
        $this->filesystem       = $filesystem;
        $this->commandRunner    = $commandRunner;
    }

    /**
     * @param Project         $project
     * @param OutputInterface $output
     * @param string          $repositoryUrl Repository in which to deploy the files.
     * @param string          $branch        Git branch in which to deploy the files.
     */
    public function deploy(Project $project, OutputInterface $output, $repositoryUrl, $branch)
    {
        $output->writeln("<comment>Deploying the website</comment>");

        $directory    = $project->targetDirectory;
        $tmpDirectory = $this->createTempDirectory();

        $this->cloneRepository($output, $repositoryUrl, $tmpDirectory);

        $this->checkoutBranch($output, $branch, $tmpDirectory);

        $this->copyGeneratedFiles($output, $directory, $tmpDirectory);

        $this->commitChanges($output, $tmpDirectory);

        $this->pushBranch($output, $branch, $tmpDirectory);

        $this->deleteTempDirectory($tmpDirectory);
    }

    private function createTempDirectory()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'couscous_deploy_');
        // Turn the temp file into a temp directory
        $this->filesystem->remove($tempFile);
        $this->filesystem->mkdir($tempFile);

        return $tempFile;
    }

    private function cloneRepository(OutputInterface $output, $repositoryUrl, $tmpDirectory)
    {
        $output->writeln("Cloning <info>$repositoryUrl</info> in <info>$tmpDirectory</info>");

        $this->commandRunner->run("git clone $repositoryUrl $tmpDirectory");
    }

    private function checkoutBranch(OutputInterface $output, $branch, $tmpDirectory)
    {
        $output->writeln("Checking out branch <info>$branch</info>");

        try {
            $this->commandRunner->run("cd '$tmpDirectory' && git checkout -b $branch origin/$branch");
        } catch (CommandException $e) {
            // The branch doesn't exist yet, so we create it
            try {
                $this->commandRunner->run("cd '$tmpDirectory' && git checkout -b $branch");
            } catch (CommandException $e) {
                throw new \RuntimeException("Unable to create the branch '$branch'" . PHP_EOL . $e->getMessage());
            }
        }
    }

    private function copyGeneratedFiles(OutputInterface $output, $directory, $tmpDirectory)
    {
        $output->writeln('Copying generated website');

        $finder = new Finder();
        $finder->files()
            ->in($tmpDirectory)
            ->ignoreVCS(true);
        $this->filesystem->remove($finder);

        $this->filesystem->mirror($directory, $tmpDirectory);
    }

    private function commitChanges(OutputInterface $output, $tmpDirectory)
    {
        $output->writeln('Committing changes');

        $message = 'Website generation with Couscous';
        $this->commandRunner->run("cd '$tmpDirectory' && git add --all . && git commit -m '$message'");
    }

    private function pushBranch(OutputInterface $output, $branch, $tmpDirectory)
    {
        $output->writeln("Pushing <info>$branch</info> (GitHub may ask you to login)");

        $this->commandRunner->run("cd '$tmpDirectory' && git push origin $branch");
    }

    private function deleteTempDirectory($dir)
    {
        $this->filesystem->remove($dir);
    }
}
