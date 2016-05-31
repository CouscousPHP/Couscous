<?php

namespace Couscous\Tests\UnitTest\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Module\Markdown\Model\TableOfContents;
use Couscous\Module\Markdown\Step\ExtractTableOfContents;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \Couscous\Module\Markdown\Step\ExtractTableOfContents
 */
class ExtractTableOfContentsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_extract_first_main_title()
    {
        $markdown = <<<MARKDOWN
# Big title

## First section

# Another big title
MARKDOWN;
        $toc = $this->getTableOfContents($markdown);
        $this->assertEquals('Big title', $toc->getTitle());
    }

    /**
     * @test
     */
    public function it_should_dump_to_html_list()
    {
        $markdown = <<<MARKDOWN
## 2.1

Some paragraph.

### 3.1

#### 4.1

#### 4.2

##### 5.1

##### 5.2

## 2.2

#### 4.1

Another paragraph.
MARKDOWN;
        $expected = <<<HTML
<ul>
    <li>
        2.1
        <ul>
            <li>
                3.1
                <ul>
                    <li>4.1</li>
                    <li>
                        4.2
                        <ul>
                            <li>5.1</li>
                            <li>5.2</li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        2.2
        <ul>
            <li>
                <ul>
                    <li>4.1</li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
HTML;
        // trim spaces
        $expected = preg_replace('/\s+\</', '<', $expected);
        $expected = preg_replace('/\>\s+/', '>', $expected);

        $toc = $this->getTableOfContents($markdown);
        $this->assertEquals($expected, $toc->toHtmlList());
    }

    /**
     * @test
     */
    public function it_should_strip_formatting()
    {
        $markdown = <<<MARKDOWN
# [Big `title`](link.md) <i class="icon"></i>

## A [section](http://example.com) with **bold**
MARKDOWN;
        $toc = $this->getTableOfContents($markdown);
        $this->assertEquals('Big title', $toc->getTitle());
        $this->assertEquals('<ul><li>A section with bold</li></ul>', $toc->toHtmlList());
    }

    /**
     * @test
     */
    public function it_should_ignore_level_1_in_sub_levels()
    {
        $markdown = <<<MARKDOWN
## Sub-section

# Level 1

## Sub-section again
MARKDOWN;
        $toc = $this->getTableOfContents($markdown);
        $this->assertEquals(null, $toc->getTitle());
        $this->assertEquals('<ul><li>Sub-section</li><li>Sub-section again</li></ul>', $toc->toHtmlList());
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

        $environment = Environment::createCommonMarkEnvironment();
        $step = new ExtractTableOfContents(new DocParser($environment), new HtmlRenderer($environment));
        $step->__invoke($repository, new NullOutput());

        $this->assertArrayHasKey('tableOfContents', $file->getMetadata());
        /** @var TableOfContents $toc */
        $toc = $file->getMetadata()['tableOfContents'];
        $this->assertTrue($toc instanceof TableOfContents);
        return $toc;
    }

}
