<?php

namespace Couscous\Tests\UnitTest\CommandRunner;

use Couscous\CommandRunner\CommandRunner;
use Couscous\CommandRunner\Git;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

#[CoversClass(Git::class)]
class GitTest extends TestCase
{
    /**
     * @var Git
     */
    private $git;

    /**
     * @var MockObject|CommandRunner
     */
    private $commandRunner;

    public function setUp(): void
    {
        parent::setUp();

        $this->commandRunner = $this->createMock('Couscous\CommandRunner\CommandRunner');
        $this->git = new Git($this->commandRunner);
    }

    #[Test]
    public function clone_should_run_git_clone()
    {
        $this->expectCommandIsRun('git clone url "directory"');

        $this->git->cloneRepository('url', 'directory');
    }

    #[Test]
    public function checkout_origin_branch_should_run_git_checkout()
    {
        $this->expectCommandIsRun('cd "directory" && git checkout -b branch origin/branch');

        $this->git->checkoutOriginBranch('directory', 'branch');
    }

    #[Test]
    public function create_branch_should_run_git_checkout()
    {
        $this->expectCommandIsRun('cd "directory" && git checkout -b branch');

        $this->git->createBranch('directory', 'branch');
    }

    #[Test]
    public function commit_should_run_git_add_and_commit()
    {
        $this->expectCommandIsRun('cd "directory" && git add --all . && git commit -m "message"');

        $this->git->commitAllChanges('directory', 'message');
    }

    #[Test]
    public function push_should_run_git_push()
    {
        $this->expectCommandIsRun('cd "directory" && git push my-remote branch');

        $this->git->push('directory', 'branch', 'my-remote');
    }

    #[Test]
    public function get_remote_url_should_return_url()
    {
        $this->expectCommandIsRun('git config --get remote.origin.url', 'the-remote');

        $remote = $this->git->getRemoteUrl('origin');

        $this->assertEquals('the-remote', $remote);
    }

    #[Test]
    public function has_uncommitted_changes_should_detect_changes()
    {
        $directory = 'directory';
        $changes = '
        changed_file_one.txt
        changed_file_two.txt
        ';
        $this->expectCommandIsRun('cd "directory" && git diff-index --name-only HEAD', $changes);

        $this->assertTrue($this->git->hasUncommittedChanges($directory));
    }

    #[Test]
    public function has_uncommitted_changes_should_detect_no_changes()
    {
        $directory = 'directory';
        $changes = '
        ';
        $this->expectCommandIsRun('cd "directory" && git diff-index --name-only HEAD', $changes);

        $this->assertFalse($this->git->hasUncommittedChanges($directory));
    }

    private function expectCommandIsRun($command, $return = '')
    {
        $this->commandRunner->expects($this->once())
            ->method('run')
            ->with($command)
            ->willReturn($return);
    }
}
