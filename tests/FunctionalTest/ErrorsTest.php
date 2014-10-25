<?php

namespace Piwik\Tests\FunctionalTest;

class ErrorsTest extends BaseFunctionalTest
{
    public function testTemplateNeeded()
    {
        $this->assertGenerationError('no-template', "The template directory doesn't exist:");
    }
}
