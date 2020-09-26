<?php
declare(strict_types = 1);

namespace Couscous\Model;

use Symfony\Component\Finder\Finder;

class ExcludeList
{
    /**
     * @var string[]
     */
    private $excluded;

    /**
     * @param list<string> $exclude
     */
    public function __construct(array $exclude = [])
    {
        $this->excluded = $exclude;
    }

    public function addEntry(string $entry): self
    {
        $this->excluded[] = $entry;

        return $this;
    }

    /**
     * @param list<string> $entries
     */
    public function addEntries(array $entries): self
    {
        $this->excluded = array_merge($this->excluded, $entries);

        return $this;
    }

    public function contains(string $needle): bool
    {
        return in_array($needle, $this->excluded);
    }

    public function toArray(): array
    {
        $excluded = $this->excluded;
        $excluded = array_filter($excluded, [$this, 'keepEntry']);
        $excluded = array_map([$this, 'sanitizeEntry'], $excluded);
        $excluded = array_map(function (string $entry): string {
            return trim($entry, '/');
        }, $excluded);

        return array_values(array_unique($excluded));
    }

    public function excludeFromFinder(Finder $finder): self
    {
        $finder->exclude($this->toArray());

        return $this;
    }

    /**
     * @param mixed $entry
     */
    private function keepEntry($entry): bool
    {
        switch (true) {
            case !is_string($entry) && !is_numeric($entry):
            case $entry === '':
            case is_string($entry) && (preg_match('/^[#!]/', $entry) > 0):
            case is_string($entry) && (strpos($entry, '*') !== false):
                return false;

            default:
                return true;
        }
    }

    private function sanitizeEntry(string $entry): string
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
