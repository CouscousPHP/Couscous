<?php

namespace Piwik\Tests\FunctionalTest;

class ErrorsTest extends BaseFunctionalTest
{
    public function testTemplateNeeded()
    {
        $dir = __DIR__ . '/fixtures';
        $message = "The template directory doesn't exist: $dir/no-template/source/website";

        $this->assertGenerationError('no-template', $message);
    }
}
