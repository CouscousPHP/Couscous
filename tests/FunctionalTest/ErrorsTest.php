<?php

declare(strict_types=1);

namespace Couscous\Tests\FunctionalTest;

class ErrorsTest extends BaseFunctionalTest
{
    public function testMissingLayout(): void
    {
        $this->assertGenerationError('missing-layout-file', 'Template "home.twig" is not defined.');
    }
}
