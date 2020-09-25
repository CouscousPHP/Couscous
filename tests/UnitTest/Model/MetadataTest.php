<?php

declare(strict_types=1);

namespace Couscous\Tests\UnitTest\Model;

use Couscous\Model\Metadata;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Couscous\Model\Metadata
 */
class MetadataTest extends TestCase
{
    private $values;

    /**
     * @var Metadata
     */
    private $metadata;

    public function setUp(): void
    {
        parent::setUp();

        $this->values = [
            'level1' => 'foo',
            'level2' => [
                'key' => 'foo',
            ],
            'level3' => [
                'key' => [
                    'key' => 'foo',
                ],
            ],
        ];
        $this->metadata = new Metadata($this->values);
    }

    public function provide_existing_keys(): array
    {
        return [
            ['level1', 'foo'],
            ['level2', ['key' => 'foo']],
            ['level2.key', 'foo'],
            ['level3', ['key' => ['key' => 'foo']]],
            ['level3.key', ['key' => 'foo']],
            ['level3.key.key', 'foo'],
        ];
    }

    public function provide_unknown_keys(): array
    {
        return [
            ['level1unknown'],
            ['level1unknown.key'],
            ['level2unknown.key'],
            ['level2.keyunknown'],
            ['level2.key.unknown'],
            ['level3unknown.key.key'],
            ['level3.keyunknown.key'],
            ['level3.key.keyunknown'],
            ['level3.key.key.unknown'],
        ];
    }

    /**
     * @test
     * @dataProvider provide_existing_keys
     */
    public function array_read_should_return_value(string $key, $expected): void
    {
        self::assertSame($expected, $this->metadata[$key]);
    }

    /**
     * @test
     * @dataProvider provide_unknown_keys
     */
    public function array_read_should_return_null_with_unknown_key(string $key): void
    {
        self::assertNull($this->metadata[$key]);
    }

    /**
     * @test
     * @dataProvider provide_existing_keys
     */
    public function array_isset_should_return_true_with_existing_key(string $key): void
    {
        self::assertTrue(isset($this->metadata[$key]));
    }

    /**
     * @test
     * @dataProvider provide_unknown_keys
     */
    public function array_isset_should_return_false_with_unknown_key(string $key): void
    {
        self::assertFalse(isset($this->metadata[$key]));
    }

    /**
     * @test
     * @dataProvider provide_existing_keys
     */
    public function array_set_should_set_value_with_existing_key(string $key): void
    {
        $this->metadata[$key] = 'hello';
        self::assertSame('hello', $this->metadata[$key]);
    }

    /**
     * @test
     * @dataProvider provide_unknown_keys
     */
    public function array_set_should_set_value_with_unknown_key(string $key): void
    {
        $this->metadata[$key] = 'hello';
        self::assertSame('hello', $this->metadata[$key]);
    }

    /**
     * @test
     * @dataProvider provide_existing_keys
     */
    public function array_unset_should_unset_value(string $key): void
    {
        self::assertTrue(isset($this->metadata[$key]));
        unset($this->metadata[$key]);
        self::assertFalse(isset($this->metadata[$key]));
    }

    /**
     * @test
     */
    public function array_set_array_with_dot_notation(): void
    {
        unset($this->metadata['foo']);

        $this->metadata['foo.bar'] = 'Hello';

        self::assertEquals('Hello', $this->metadata['foo.bar']);
        self::assertEquals('Hello', $this->metadata['foo']['bar']);
    }

    /**
     * @test
     */
    public function toArray_should_return_array(): void
    {
        self::assertSame($this->values, $this->metadata->toArray());
    }

    /**
     * @test
     */
    public function setMany_should_merge_values(): void
    {
        $metadata = new Metadata([
            'a' => 'a',
            'b' => 'b',
        ]);
        $metadata->setMany([
            'b' => 'test',
            'c' => 'c',
        ]);
        $expected = [
            'a' => 'a',
            'b' => 'test',
            'c' => 'c',
        ];
        self::assertEquals($expected, $metadata->toArray());
    }
}
