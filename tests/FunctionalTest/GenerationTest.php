<?php

namespace Piwik\Tests\FunctionalTest;

class GenerationTest extends BaseFunctionalTest
{
    public function testBasic()
    {
        $this->assertGeneratedWebsite('basic');
    }

    public function testDefaultTemplate()
    {
        $this->assertGeneratedWebsite('default-template');
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
     * Test custom variables defined:
     *     - in FrontYAML inside Markdown files
     *     - in couscous.yml
     */
    public function testCustomVariables()
    {
        $this->assertGeneratedWebsite('custom-variables');
    }

    /**
     * Test the pageList and pageTree template variables.
     */
    public function testPageList()
    {
        $this->assertGeneratedWebsite('page-list');
    }
}
