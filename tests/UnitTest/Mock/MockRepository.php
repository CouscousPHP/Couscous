<?php

namespace Couscous\Tests\UnitTest\Mock;

use Couscous\Model\Metadata;
use Couscous\Model\Repository;

class MockRepository extends Repository
{
    public function __construct()
    {
        parent::__construct('', '');

        $this->metadata    = new Metadata();
        $this->watchlist = new MockWatchList();
    }
}
