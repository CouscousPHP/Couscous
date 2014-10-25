<?php

namespace Piwik\Tests\FunctionalTest;

class BasicTest extends BaseFunctionalTest
{
    public function testTemplateNeeded()
    {
        $this->assertGeneratedWebsite('basic');
    }
}
