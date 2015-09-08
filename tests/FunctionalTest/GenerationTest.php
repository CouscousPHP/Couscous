<?php

namespace Couscous\tests\FunctionalTest;

class GenerationTest extends BaseFunctionalTest
{
    public function testBasic()
    {
        $this->assertGeneratedWebsite('basic');
    }

    public function testHeadings()
    {
        $this->assertGeneratedWebsite('headings');
    }

    public function testMissingPublicDirectory()
    {
        $this->assertGeneratedWebsite('missing-public-directory');
    }

    public function testAssetsAreCopied()
    {
        $this->assertGeneratedWebsite('assets');
    }

    /**
     * Test metadata defined:
     *     - in FrontYAML inside Markdown files
     *     - in couscous.yml.
     */
    public function testMetadata()
    {
        $this->assertGeneratedWebsite('metadata');
    }

    /**
     * Test the pageList and pageTree template variables.
     */
    public function testPageList()
    {
        $this->assertGeneratedWebsite('page-list');
    }

    /**
     * Test that the images referenced by documentation are copied.
     */
    public function testDocumentationImages()
    {
        $this->assertGeneratedWebsite('images');
    }

    /**
     * Test that the currentFile variable is available.
     */
    public function testCurrentFileVariable()
    {
        $this->assertGeneratedWebsite('current-file');
    }
}
