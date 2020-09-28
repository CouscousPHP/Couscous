<?php

namespace Couscous\Tests\UnitTest\Mock;

use Couscous\Model\WatchList\WatchList;
use Symfony\Component\Finder\Finder;

class MockWatchList extends WatchList
{
    public function watchFile(string $filename): void
    {
    }

    public function watchFiles(Finder $finder): void
    {
    }

    public function watchDirectory(string $directory): void
    {
    }

    public function getChangedFiles(): array
    {
        return [];
    }
}
