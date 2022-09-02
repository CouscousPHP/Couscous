<?php
declare(strict_types = 1);

namespace Couscous\Model;

/**
 * Contains metadata, accessible through array access:.
 *
 *     $metadata['foo'] = 'test';
 *     echo $metadata['foo'];
 *
 *     $metadata['foo'] = ['bar' => 'hello'];
 *     echo $metadata['foo']['bar'];
 *     // Or also
 *     echo $metadata['foo.bar'];
 *
 *     // Non-existing keys just return null when using the `.` notation
 *     echo $metadata['this.is.an.unknown.key'];
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Metadata implements \ArrayAccess
{
    /**
     * @var array
     */
    private $values;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @param string $offset
     */
    public function offsetExists($offset): bool
    {
        $keys = explode('.', $offset);

        return $this->recursiveExist($keys, $this->values);
    }

    /**
     * @param string $offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        $keys = explode('.', $offset);

        return $this->recursiveGet($keys, $this->values);
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $keys = explode('.', $offset);

        /** @psalm-suppress MixedAssignment Todo refacto to avoid references */
        $this->recursiveSet($keys, $this->values, $value);
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset): void
    {
        $this[$offset] = null;
    }

    /**
     * Merge metadata into the current metadata instance.
     */
    public function setMany(array $metadata): void
    {
        $this->values = array_merge($this->values, $metadata);
    }

    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * @param list<string> $keys
     * @param mixed $values
     *
     * @return mixed
     */
    private function recursiveGet(array $keys, $values)
    {
        $key = array_shift($keys);

        if (!is_array($values)) {
            return;
        }

        if (!isset($values[$key])) {
            return;
        }

        if (empty($keys)) {
            return $values[$key];
        }

        return $this->recursiveGet($keys, $values[$key]);
    }

    /**
     * @param list<string> $keys
     * @param mixed $values
     */
    private function recursiveExist(array $keys, $values): bool
    {
        $key = array_shift($keys);

        if (!is_array($values)) {
            return false;
        }

        if (!isset($values[$key])) {
            return false;
        }

        if (empty($keys)) {
            return true;
        }

        return $this->recursiveExist($keys, $values[$key]);
    }

    /**
     * @param list<string> $keys
     * @param mixed &$values
     * @param mixed $value
     */
    private function recursiveSet(array $keys, &$values, $value): void
    {
        $key = array_shift($keys);

        if (!is_array($values)) {
            $values = [];
        }

        if (empty($keys)) {
            /** @psalm-suppress MixedAssignment */
            $values[$key] = $value;

            return;
        }

        if (!isset($values[$key])) {
            $values[$key] = [];
        }

        $this->recursiveSet($keys, $values[$key], $value);
    }
}
