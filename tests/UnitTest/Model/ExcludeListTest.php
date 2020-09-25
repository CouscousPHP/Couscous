<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\ExcludeList;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * @covers \Couscous\Model\ExcludeList
 */
class ExcludeListTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_init_with_excluded_entries(): void
    {
        $excluded = new ExcludeList(['foo', 'bar']);

        self::assertSame(['foo', 'bar'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_store_additional_entry(): void
    {
        $excluded = new ExcludeList(['foo', 'bar']);
        $excluded->addEntry('baz');

        self::assertSame(['foo', 'bar', 'baz'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_store_additional_entries(): void
    {
        $excluded = new ExcludeList(['foo', 'bar']);
        $excluded->addEntries(['baz', 'boo']);

        self::assertSame(['foo', 'bar', 'baz', 'boo'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_dedupe_entries(): void
    {
        $excluded = new ExcludeList(['foo', 'bar', 'baz']);
        $excluded->addEntry('foo');
        $excluded->addEntries(['baz', 'boo']);

        self::assertSame(['foo', 'bar', 'baz', 'boo'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_filter_invalid_entries(): void
    {
        $excluded = new ExcludeList(['foo', '', 'bar']);
        $excluded->addEntry('');
        $excluded->addEntries(['baz', '', null, true, false, 1337]);

        self::assertSame(['foo', 'bar', 'baz', '1337'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_filter_special_entries(): void
    {
        $excluded = new ExcludeList(['foo', '#foo', 'bar', '!bar', '*.php', 'foo/**/bar.php']);

        self::assertSame(['foo', 'bar'], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_sanitize_entries(): void
    {
        $excluded = new ExcludeList(['foo', 'bar ', "baz\t", 'boo\ ']);

        self::assertSame(['foo', 'bar', 'baz', 'boo '], $excluded->toArray());
    }

    /**
     * @test
     */
    public function it_should_exclude_from_finder(): void
    {
        /** @var MockObject&Finder $finder */
        $finder = $this->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $finder->expects(self::once())->method('exclude')->with(['foo', 'bar', '1337']);

        $excluded = new ExcludeList(['foo', '#foo', 'bar', '!bar', '', 1337, null]);
        $excluded->excludeFromFinder($finder);
    }
}
