<?php

namespace Couscous\Tests\UnitTest\Module\Markdown\Step;

use Couscous\Application\ContainerFactory;
use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Module\Markdown\Step\RenderMarkdown;

/**
 * Test supported Markdown features.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RenderMarkdownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that classic Markdown is supported.
     */
    public function testClassicMarkdown()
    {
        $markdown = <<<'MARKDOWN'
# Title 1
## Title 2
### Title 3
#### Title 4
##### Title 5
###### Title 6

Hello *world*! This is a [link](http://github.com).

    this is a
    code block

> this is a quote
> that spans on two lines

- one
- two

1. one
2. two
MARKDOWN;
        $html = <<<'HTML'
<h1>Title 1</h1>
<h2>Title 2</h2>
<h3>Title 3</h3>
<h4>Title 4</h4>
<h5>Title 5</h5>
<h6>Title 6</h6>
<p>Hello <em>world</em>! This is a <a href="http://github.com">link</a>.</p>
<pre><code>this is a
code block</code></pre>
<blockquote>
<p>this is a quote
that spans on two lines</p>
</blockquote>
<ul>
<li>one</li>
<li>two</li>
</ul>
<ol>
<li>one</li>
<li>two</li>
</ol>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testFencedCodeBlocks()
    {
        $markdown = <<<'MARKDOWN'
```
Test 1
Test 2
```

```php
Test 1
```
MARKDOWN;
        $html = <<<'HTML'
<pre><code>Test 1
Test 2</code></pre>
<pre><code class="language-php">Test 1</code></pre>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testInlineHtml()
    {
        $this->assertGeneratedHtml(
            'This is a paragraph with a <strong>world</strong>.',
            '<p>This is a paragraph with a <strong>world</strong>.</p>'
        );
    }

    public function testMarkdownInHtml1()
    {
        $markdown = <<<'MARKDOWN'
<div markdown="1">
Hello *world*!
</div>
MARKDOWN;
        $html = <<<'HTML'
<div>
<p>Hello <em>world</em>!</p>
</div>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testMarkdownInHtml2()
    {
        $markdown = <<<'MARKDOWN'
<div markdown="1">

Hello *world*!

</div>
MARKDOWN;
        $html = <<<'HTML'
<div>
<p>Hello <em>world</em>!</p>
</div>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testAttributes()
    {
        $markdown = <<<'MARKDOWN'
# Header 1       {#header1}

## Header 2      {#header2}
MARKDOWN;
        $html = <<<'HTML'
<h1 id="header1">Header 1</h1>
<h2 id="header2">Header 2</h2>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testTables1()
    {
        $markdown = <<<'MARKDOWN'
First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell
Content Cell  | Content Cell
MARKDOWN;
        $html = <<<'HTML'
<table>
<thead>
<tr>
<th>First Header</th>
<th>Second Header</th>
</tr>
</thead>
<tbody>
<tr>
<td>Content Cell</td>
<td>Content Cell</td>
</tr>
<tr>
<td>Content Cell</td>
<td>Content Cell</td>
</tr>
</tbody>
</table>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testTables2()
    {
        $markdown = <<<'MARKDOWN'
| Function name | Description                    |
| ------------- | ------------------------------:|
| `help()`      | Display the help window.       |
| `destroy()`   | **Destroy your computer!**     |
MARKDOWN;
        $html = <<<'HTML'
<table>
<thead>
<tr>
<th>Function name</th>
<th style="text-align: right;">Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>help()</code></td>
<td style="text-align: right;">Display the help window.</td>
</tr>
<tr>
<td><code>destroy()</code></td>
<td style="text-align: right;"><strong>Destroy your computer!</strong></td>
</tr>
</tbody>
</table>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testDefinitionLists()
    {
        $markdown = <<<'MARKDOWN'
Apple
:   Pomaceous fruit of plants of the genus Malus in
the family Rosaceae.

Orange
:   The fruit of an evergreen tree of the genus Citrus.
MARKDOWN;
        $html = <<<'HTML'
<dl>
<dt>Apple</dt>
<dd>Pomaceous fruit of plants of the genus Malus in
the family Rosaceae.</dd>
<dt>Orange</dt>
<dd>The fruit of an evergreen tree of the genus Citrus.</dd>
</dl>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testFootnotes()
    {
        $markdown = <<<'MARKDOWN'
That's some text with a footnote.[^1]

[^1]: And that's the footnote.
MARKDOWN;
        $html = <<<'HTML'
<p>That's some text with a footnote.<sup id="fnref1:1"><a href="#fn:1" class="footnote-ref">1</a></sup></p>
<div class="footnotes">
<hr />
<ol>
<li id="fn:1">
<p>And that's the footnote.&#160;<a href="#fnref1:1" rev="footnote" class="footnote-backref">&#8617;</a></p>
</li>
</ol>
</div>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testAbbreviations()
    {
        $markdown = <<<'MARKDOWN'
The HTML specification is maintained by the W3C.

*[HTML]: Hyper Text Markup Language
*[W3C]:  World Wide Web Consortium
MARKDOWN;
        $html = <<<'HTML'
<p>The <abbr title="Hyper Text Markup Language">HTML</abbr> specification is maintained by the <abbr title="World Wide Web Consortium">W3C</abbr>.</p>
HTML;
        $this->assertGeneratedHtml($markdown, $html);
    }

    public function testEmphasisInWords()
    {
        $this->assertGeneratedHtml(
            'Please open the folder secret_magic_box.',
            '<p>Please open the folder secret_magic_box.</p>'
        );
    }

    private function assertGeneratedHtml($markdown, $expectedHtml)
    {
        $container = (new ContainerFactory())->createContainer();
        /** @var RenderMarkdown $step */
        $step = $container->get('Couscous\Module\Markdown\Step\RenderMarkdown');

        $project = new Project('foo', 'bar');
        $project->addFile(new MarkdownFile('foo.md', $markdown));

        $step->__invoke($project);

        $files = $project->getFiles();

        $this->assertCount(1, $files);
        /** @var MarkdownFile $newFile */
        $newFile = reset($files);

        $this->assertEquals($expectedHtml, $newFile->getContent());
    }
}
