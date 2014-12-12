<?php

namespace Couscous\Tests\UnitTest\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Module\Markdown\Step\ProcessMarkdownLinks;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Couscous\Step\Markdown\ProcessMarkdownLinks
 */
class ProcessMarkdownLinksTest extends \PHPUnit_Framework_TestCase
{
    public function testReplaceLinks()
    {
        $markdown = <<<MARKDOWN
This is a [link](doc/some-other.file.md), can you handle it (even with these parentheses)?

Please leave [this](doc/test.html) and [this link](test.md.txt) alone.
MARKDOWN;

        $expected = <<<MARKDOWN
This is a [link](doc/some-other.file.html), can you handle it (even with these parentheses)?

Please leave [this](doc/test.html) and [this link](test.md.txt) alone.
MARKDOWN;

        $file = new MarkdownFile('foo', $markdown);
        $repository = new Repository('foo', 'bar');
        $repository->addFile($file);

        $step = new ProcessMarkdownLinks();
        $step->__invoke($repository, new NullOutput());

        $this->assertEquals($expected, $file->content);
    }
}
