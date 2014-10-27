<?php

namespace Piwik\Tests\FunctionalTest;

class GenerationTest extends BaseFunctionalTest
{
    public function testBasic()
    {
        $this->assertGeneratedWebsite('basic');
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
