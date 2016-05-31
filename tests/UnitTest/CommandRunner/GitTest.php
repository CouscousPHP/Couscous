<?php

namespace Couscous\Tests\UnitTest\CommandRunner;

use Couscous\CommandRunner\CommandRunner;
use Couscous\CommandRunner\Git;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @covers \Couscous\CommandRunner\Git
 */
class GitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Git
     */
    private $git;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|CommandRunner
     */
    private $commandRunner;

    public function setUp()
    {
        parent::setUp();

        $this->commandRunner = $this->getMock('Couscous\CommandRunner\CommandRunner');
        $this->git = new Git($this->commandRunner);
    }

    /**
     * @test
     */
    public function clone_should_run_git_clone()
    {
        $this->expectCommandIsRun('git clone url directory');

        $this->git->cloneRepository('url', 'directory');
    }

    /**
     * @test
     */
    public function checkout_origin_branch_should_run_git_checkout()
    {
        $this->expectCommandIsRun('cd "directory" && git checkout -b branch origin/branch');

        $this->git->checkoutOriginBranch('directory', 'branch');
    }

    /**
     * @test
     */
    public function create_branch_should_run_git_checkout()
    {
        $this->expectCommandIsRun('cd "directory" && git checkout -b branch');

        $this->git->createBranch('directory', 'branch');
    }

    /**
     * @test
     */
    public function commit_should_run_git_add_and_commit()
    {
        $this->expectCommandIsRun('cd "directory" && git add --all . && git commit -m "message"');

        $this->git->commitAllChanges('directory', 'message');
    }

    /**
     * @test
     */
    public function push_should_run_git_push()
    {
        $this->expectCommandIsRun('cd "directory" && git push my-remote branch');

        $this->git->push('directory', 'branch', 'my-remote');
    }

    /**
     * @test
     */
    public function get_remote_url_should_return_url()
    {
        $this->expectCommandIsRun('git config --get remote.origin.url', 'the-remote');

        $remote = $this->git->getRemoteUrl('origin');

        $this->assertEquals('the-remote', $remote);
    }

    private function expectCommandIsRun($command, $return = null)
    {
        $this->commandRunner->expects($this->once())
            ->method('run')
            ->with($command)
            ->willReturn($return);
    }
}
