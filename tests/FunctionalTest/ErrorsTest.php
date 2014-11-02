<?php

namespace Piwik\Tests\FunctionalTest;

class ErrorsTest extends BaseFunctionalTest
{
    public function testMissingTemplate()
    {
        $this->assertGenerationError('missing-layout-file', 'Template "home.twig" is not defined.');
    }
}
