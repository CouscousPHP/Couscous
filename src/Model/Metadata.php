<?php

namespace Couscous\Model;

/**
 * Metadata.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Metadata implements \ArrayAccess
{
    /**
     * @var array
     */
    private $metadata = [];

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->metadata);
    }

    public function offsetGet($offset)
    {
        if (! isset($this->metadata[$offset])) {
            return null;
        }

        return $this->metadata[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->metadata[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->metadata[$offset]);
    }

    /**
     * Merge metadata into the current metadata instance.
     *
     * @param array $metadata
     */
    public function setMany(array $metadata)
    {
        $this->metadata = array_merge($this->metadata, $metadata);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->metadata;
    }
}
