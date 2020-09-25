<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Module\Template\Step;

use Couscous\Model\Project;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Module\Template\Step\AddPageListToLayoutVariables;
use Couscous\Tests\UnitTest\Mock\MockProject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Module\Template\Step\AddPageListToLayoutVariables
 */
class AddPageListToTemplateVariablesTest extends TestCase
{
    private function files(): array
    {
        return [
            new HtmlFile('index.html', ''),
            new HtmlFile('docs/index.html', ''),
            new HtmlFile('docs/foo.html', ''),
            new HtmlFile('docs/subdirectory/bar.html', ''),
            new HtmlFile('docs/sub/sub/foo.html', ''),
            new HtmlFile('weird.path-test [foo]/bar.html', ''),
        ];
    }

    public function testPageList(): void
    {
        $project = new MockProject();

        $this->invokeStep($project, $this->files());

        $expected = [
            'index.html',
            'docs/index.html',
            'docs/foo.html',
            'docs/subdirectory/bar.html',
            'docs/sub/sub/foo.html',
            'weird.path-test [foo]/bar.html',
        ];

        self::assertEquals($expected, $project->metadata['pageList']);
    }

    public function testPageTree(): void
    {
        $project = new MockProject();

        $this->invokeStep($project, $this->files());

        $expected = [
            'docs' => [
                'foo.html'     => 'foo.html',
                'index.html'   => 'index.html',
                'subdirectory' => [
                    'bar.html' => 'bar.html',
                ],
                'sub' => [
                    'sub' => [
                        'foo.html' => 'foo.html',
                    ],
                ],
            ],
            'index.html'            => 'index.html',
            'weird.path-test [foo]' => [
                'bar.html' => 'bar.html',
            ],
        ];

        self::assertEquals($expected, $project->metadata['pageTree']);
    }

    private function invokeStep(Project $project, $files): void
    {
        foreach ($files as $file) {
            $project->addFile($file);
        }

        $step = new AddPageListToLayoutVariables();
        $step->__invoke($project);
    }
}
