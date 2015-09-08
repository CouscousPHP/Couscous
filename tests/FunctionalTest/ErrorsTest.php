<?php

namespace Couscous\tests\FunctionalTest;

class ErrorsTest extends BaseFunctionalTest
{
    public function testMissingLayout()
    {
        $this->assertGenerationError('missing-layout-file', 'Template "home.twig" is not defined.');
    }
}
