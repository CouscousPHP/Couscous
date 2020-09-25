<?php

declare(strict_types=1);

namespace Couscous\Tests\FunctionalTest;

class GenerationTest extends BaseFunctionalTest
{
    public function testBasic(): void
    {
        $this->assertGeneratedWebsite('basic');
    }

    public function testHeadings(): void
    {
        $this->assertGeneratedWebsite('headings');
    }

    public function testMissingPublicDirectory(): void
    {
        $this->assertGeneratedWebsite('missing-public-directory');
    }

    public function testAssetsAreCopied(): void
    {
        $this->assertGeneratedWebsite('assets');
    }

    /**
     * Test metadata defined:
     *     - in FrontYAML inside Markdown files
     *     - in couscous.yml.
     */
    public function testMetadata(): void
    {
        $this->assertGeneratedWebsite('metadata');
    }

    /**
     * Test the pageList and pageTree template variables.
     */
    public function testPageList(): void
    {
        $this->assertGeneratedWebsite('page-list');
    }

    /**
     * Test that the images referenced by documentation are copied.
     */
    public function testDocumentationImages(): void
    {
        $this->assertGeneratedWebsite('images');
    }

    /**
     * Test that the currentFile variable is available.
     */
    public function testCurrentFileVariable(): void
    {
        $this->assertGeneratedWebsite('current-file');
    }

    /**
     * Test the CNAME file generation.
     */
    public function testCname(): void
    {
        $this->assertGeneratedWebsite('cname');
    }

    /**
     * Test the include configuration option.
     */
    public function testIncludeOnly(): void
    {
        $this->assertGeneratedWebsite('include-only');
    }

    /**
     * Test the include configuration option with an exclude option set.
     */
    public function testIncludeWithExclude(): void
    {
        $this->assertGeneratedWebsite('include-with-exclude');
    }
}
