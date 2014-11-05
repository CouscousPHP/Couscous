<?php

namespace Couscous\Tests\FunctionalTest;

class ErrorsTest extends BaseFunctionalTest
{
    public function testMissingLayout()
    {
        $this->assertGenerationError('missing-layout-file', 'Template "home.twig" is not defined.');
    }
}
