<?php

namespace Couscous\Tests\UnitTest\Mock;

use Couscous\Model\Config;
use Couscous\Model\Repository;

class MockRepository extends Repository
{
    public function __construct()
    {
        parent::__construct('', '');

        $this->config    = new Config();
        $this->watchlist = new MockWatchList();
    }
}
