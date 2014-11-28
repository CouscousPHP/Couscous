<?php

namespace Couscous\Tests\UnitTest\Step\Template;

use Couscous\Model\File\HtmlFile;
use Couscous\Model\Repository;
use Couscous\Step\Template\AddPageListToLayoutVariables;
use Couscous\Tests\UnitTest\Mock\MockRepository;
use Symfony\Component\Console\Output\NullOutput;

class AddPageListToTemplateVariablesTest extends \PHPUnit_Framework_TestCase
{
    private function files()
    {
        $files = array(
            new HtmlFile('index.html', ''),
            new HtmlFile('docs/index.html', ''),
            new HtmlFile('docs/foo.html', ''),
            new HtmlFile('docs/subdirectory/bar.html', ''),
            new HtmlFile('docs/sub/sub/foo.html', ''),
            new HtmlFile('weird.path-test [foo]/bar.html', ''),
        );
        return $files;
    }

    public function testPageList()
    {
        $repository = new MockRepository();

        $this->invokeStep($repository, $this->files());

        $expected = array(
            'index.html',
            'docs/index.html',
            'docs/foo.html',
            'docs/subdirectory/bar.html',
            'docs/sub/sub/foo.html',
            'weird.path-test [foo]/bar.html',
        );

        $this->assertEquals($expected, $repository->metadata['pageList']);
    }

    public function testPageTree()
    {
        $repository = new MockRepository();

        $this->invokeStep($repository, $this->files());

        $expected = array(
            'docs' => array(
                'foo.html' => 'foo.html',
                'index.html' => 'index.html',
                'subdirectory' => array(
                    'bar.html' => 'bar.html',
                ),
                'sub' => array(
                    'sub' => array(
                        'foo.html' => 'foo.html',
                    ),
                ),
            ),
            'index.html' => 'index.html',
            'weird.path-test [foo]' => array(
                'bar.html' => 'bar.html',
            ),
        );

        $this->assertEquals($expected, $repository->metadata['pageTree']);
    }

    private function invokeStep(Repository $repository, $files)
    {
        foreach ($files as $file) {
            $repository->addFile($file);
        }

        $step = new AddPageListToLayoutVariables();
        $step->__invoke($repository, new NullOutput());
    }
}
