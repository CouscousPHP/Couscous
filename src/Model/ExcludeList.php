<?php

namespace Couscous\Model;

use Symfony\Component\Finder\Finder;

class ExcludeList
{
    /**
     * @var string[]
     */
    private $excluded;

    public function __construct(array $exclude = [])
    {
        $this->excluded = $exclude;
    }

    public function addEntry($entry)
    {
        $this->excluded[] = $entry;

        return $this;
    }

    public function addEntries(array $entries)
    {
        $this->excluded = array_merge($this->excluded, $entries);

        return $this;
    }

    public function contains($needle)
    {
        return in_array($needle, $this->excluded);
    }

    public function toArray()
    {
        $excluded = $this->excluded;
        $excluded = array_filter($excluded, [$this, 'keepEntry']);
        $excluded = array_map([$this, 'sanitizeEntry'], $excluded);
        $excluded = array_map(function ($entry) {
            return trim($entry, '/');
        }, $excluded);

        return array_values(array_unique($excluded));
    }

    public function excludeFromFinder(Finder $finder)
    {
        $finder->exclude($this->toArray());

        return $this;
    }

    private function keepEntry($entry)
    {
        switch (true) {
            case !is_string($entry) && !is_numeric($entry):
            case $entry === '':
            case preg_match('/^[#!]/', $entry) > 0:
            case strpos($entry, '*') !== false:
                return false;

            default:
                return true;
        }
    }

    private function sanitizeEntry($entry)
    {
        return preg_replace(
            '/\\\(\s)$/',
            '$1',
            preg_replace(
                '/(?<!\\\)(\s)$/',
                '',
                $entry
            )
        );
    }
}
