<?php

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
    private $values = [];

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public function offsetExists($offset)
    {
        $keys = explode('.', $offset);

        return $this->recursiveExist($keys, $this->values);
    }

    public function offsetGet($offset)
    {
        $keys = explode('.', $offset);

        return $this->recursiveGet($keys, $this->values);
    }

    public function offsetSet($offset, $value)
    {
        $keys = explode('.', $offset);

        $this->recursiveSet($keys, $this->values, $value);
    }

    public function offsetUnset($offset)
    {
        $this[$offset] = null;
    }

    /**
     * Merge metadata into the current metadata instance.
     *
     * @param array $metadata
     */
    public function setMany(array $metadata)
    {
        $this->values = array_merge($this->values, $metadata);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->values;
    }

    private function recursiveGet($keys, $values)
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

    private function recursiveExist($keys, $values)
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

    private function recursiveSet($keys, &$values, $value)
    {
        $key = array_shift($keys);

        if (!is_array($values)) {
            $values = [];
        }

        if (empty($keys)) {
            $values[$key] = $value;

            return;
        }

        if (!isset($values[$key])) {
            $values[$key] = [];
        }

        $this->recursiveSet($keys, $values[$key], $value);
    }
}
