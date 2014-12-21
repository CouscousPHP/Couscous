<?php

namespace Couscous\Tests\UnitTest\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Module\Markdown\Model\TableOfContents;
use Couscous\Module\Markdown\Step\ExtractTableOfContents;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Couscous\Module\Markdown\Step\ExtractTableOfContents
 */
class ExtractTableOfContentsTest extends \PHPUnit_Framework_TestCase
{
    private $markdown = <<<MARKDOWN
# Big title

## First section

Some paragraph.

### Sub-section

## Second section

Another paragraph.
MARKDOWN;

    /**
     * @test
     */
    public function it_should_extract_main_title()
    {
        $toc = $this->getTableOfContents($this->markdown);

        $this->assertEquals('Big title', $toc->getTitle());
        $this->assertEquals(['Big title'], $toc->getHeaders(1));
    }

    /**
     * @test
     */
    public function it_should_extract_second_level_headers()
    {
        $expected = [
            'First section',
            'Second section',
        ];

        $toc = $this->getTableOfContents($this->markdown);
        $this->assertEquals($expected, $toc->getHeaders(2));
    }

    /**
     * @test
     */
    public function it_should_strip_formatting()
    {
        $markdown = <<<MARKDOWN
# Big `title`

## A [section](http://example.com) with **bold**
MARKDOWN;
        $expected = [
            1 => [
                'Big title',
            ],
            2 => [
                'A section with bold',
            ],
        ];

        $toc = $this->getTableOfContents($markdown);
        $this->assertEquals($expected, $toc->getAllHeaders());
    }

    /**
     * @param string $markdown
     * @return TableOfContents
     */
    private function getTableOfContents($markdown)
    {
        $file = new MarkdownFile('foo', $markdown);
        $repository = new Repository('foo', 'bar');
        $repository->addFile($file);

        $step = new ExtractTableOfContents();
        $step->__invoke($repository, new NullOutput());

        $this->assertArrayHasKey('tableOfContents', $file->getMetadata());
        /** @var TableOfContents $toc */
        $toc = $file->getMetadata()['tableOfContents'];
        $this->assertTrue($toc instanceof TableOfContents);
        return $toc;
    }

}
