<?php

namespace Piwik\Tests\FunctionalTest;

class ErrorsTest extends BaseFunctionalTest
{
    public function testMissingTemplate()
    {
        $this->assertGenerationError('missing-template-file', 'There was an error while rendering the file "index.html" with the template "home.twig": Template "home.twig" is not defined.');
    }
}
