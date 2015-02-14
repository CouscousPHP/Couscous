<?php

namespace Couscous\Tests\FunctionalTest;

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
     *     - in couscous.yml
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
}
