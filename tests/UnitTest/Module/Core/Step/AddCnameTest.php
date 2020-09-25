<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Core\Step;

use Couscous\Model\Metadata;
use Couscous\Model\Project;
use Couscous\Module\Core\Step\AddCname;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Model\Project
 */
class AddCnameTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_add_the_cname_file(): void
    {
        $project = new Project('foo', 'bar');

        $project->metadata = new Metadata();
        $project->metadata['cname'] = 'https://www.couscous.io';

        $step = new AddCname();
        $step->__invoke($project);

        $cnameFiles = $project->findFilesByType('Couscous\Module\Template\Model\CnameFile');

        self::assertCount(1, $cnameFiles);

        self::assertEquals($cnameFiles['CNAME']->content, $project->metadata['cname']);
    }
}
