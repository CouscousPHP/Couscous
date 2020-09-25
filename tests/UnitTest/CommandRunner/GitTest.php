<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\CommandRunner;

use Couscous\CommandRunner\CommandRunner;
use Couscous\CommandRunner\Git;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\CommandRunner\Git
 */
class GitTest extends TestCase
{
    /**
     * @var Git
     */
    private $git;

    /**
     * @var MockObject&CommandRunner
     */
    private $commandRunner;

    public function setUp(): void
    {
        parent::setUp();

        $this->commandRunner = $this->createMock(CommandRunner::class);
        $this->git = new Git($this->commandRunner);
    }

    /**
     * @test
     */
    public function clone_should_run_git_clone(): void
    {
        $this->expectCommandIsRun('git clone url "directory"');

        $this->git->cloneRepository('url', 'directory');
    }

    /**
     * @test
     */
    public function checkout_origin_branch_should_run_git_checkout(): void
    {
        $this->expectCommandIsRun('cd "directory" && git checkout -b branch origin/branch');

        $this->git->checkoutOriginBranch('directory', 'branch');
    }

    /**
     * @test
     */
    public function create_branch_should_run_git_checkout(): void
    {
        $this->expectCommandIsRun('cd "directory" && git checkout -b branch');

        $this->git->createBranch('directory', 'branch');
    }

    /**
     * @test
     */
    public function commit_should_run_git_add_and_commit(): void
    {
        $this->expectCommandIsRun('cd "directory" && git add --all . && git commit -m "message"');

        $this->git->commitAllChanges('directory', 'message');
    }

    /**
     * @test
     */
    public function push_should_run_git_push(): void
    {
        $this->expectCommandIsRun('cd "directory" && git push my-remote branch');

        $this->git->push('directory', 'branch', 'my-remote');
    }

    /**
     * @test
     */
    public function get_remote_url_should_return_url(): void
    {
        $this->expectCommandIsRun('git config --get remote.origin.url', 'the-remote');

        $remote = $this->git->getRemoteUrl('origin');

        self::assertEquals('the-remote', $remote);
    }

    /**
     * @test
     */
    public function has_uncommitted_changes_should_detect_changes(): void
    {
        $directory = 'directory';
        $changes = '
        changed_file_one.txt
        changed_file_two.txt
        ';
        $this->expectCommandIsRun('cd "directory" && git diff-index --name-only HEAD', $changes);

        self::assertTrue($this->git->hasUncommittedChanges($directory));
    }

    /**
     * @test
     */
    public function has_uncommitted_changes_should_detect_no_changes(): void
    {
        $directory = 'directory';
        $changes = '
        ';
        $this->expectCommandIsRun('cd "directory" && git diff-index --name-only HEAD', $changes);

        self::assertFalse($this->git->hasUncommittedChanges($directory));
    }

    private function expectCommandIsRun($command, $return = ''): void
    {
        $this->commandRunner->expects(self::once())
            ->method('run')
            ->with($command)
            ->willReturn($return);
    }
}
