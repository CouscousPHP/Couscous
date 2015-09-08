<?php

namespace Couscous\tests\UnitTest\Mock;

use Couscous\Model\WatchList\WatchList;
use Symfony\Component\Finder\Finder;

class MockWatchList extends WatchList
{
    public function watchFile($filename)
    {
    }

    public function watchFiles(Finder $finder)
    {
    }

    public function watchDirectory($directory)
    {
    }

    public function getChangedFiles()
    {
        return [];
    }
}
