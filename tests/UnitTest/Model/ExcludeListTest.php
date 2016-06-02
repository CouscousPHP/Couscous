<?php

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\ExcludeList;

/**
 * @covers \Couscous\Model\ExcludeList
 */
class ExcludeListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_init_with_excluded_entries()
    {
        $excluded = new ExcludeList(['foo', 'bar']);

        $this->assertSame(['foo', 'bar'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_store_additional_entry()
    {
        $excluded = new ExcludeList(['foo', 'bar']);
        $excluded->addEntry('baz');

        $this->assertSame(['foo', 'bar', 'baz'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_store_additional_entries()
    {
        $excluded = new ExcludeList(['foo', 'bar']);
        $excluded->addEntries(['baz', 'boo']);

        $this->assertSame(['foo', 'bar', 'baz', 'boo'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_dedupe_entries()
    {
        $excluded = new ExcludeList(['foo', 'bar', 'baz']);
        $excluded->addEntry('foo');
        $excluded->addEntries(['baz', 'boo']);

        $this->assertSame(['foo', 'bar', 'baz', 'boo'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_filter_invalid_entries()
    {
        $excluded = new ExcludeList(['foo', '', 'bar']);
        $excluded->addEntry('');
        $excluded->addEntries(['baz', '', null, true, false, 1337]);

        $this->assertSame(['foo', 'bar', 'baz', '1337'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_filter_special_entries()
    {
        $excluded = new ExcludeList(['foo', '#foo', 'bar', '!bar', '*.php', 'foo/**/bar.php']);

        $this->assertSame(['foo', 'bar'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_sanitize_entries()
    {
        $excluded = new ExcludeList(['foo', 'bar ', "baz\t", 'boo\ ']);

        $this->assertSame(['foo', 'bar', 'baz', 'boo '], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_exclude_from_finder()
    {
        $finder = $this->getMockBuilder('Symfony\Component\Finder\Finder')
            ->disableOriginalConstructor()
            ->getMock();
        $finder->expects($this->once())->method('exclude')->with(['foo', 'bar', '1337']);

        $excluded = new ExcludeList(['foo', '#foo', 'bar', '!bar', '', 1337, null]);
        $excluded->excludeFromFinder($finder);
    }
}
