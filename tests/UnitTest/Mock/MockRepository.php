<?php

namespace Couscous\Tests\UnitTest\Mock;

use Couscous\Model\RepositoryMetadata;
use Couscous\Model\Repository;

class MockRepository extends Repository
{
    public function __construct()
    {
        parent::__construct('', '');

        $this->metadata    = new RepositoryMetadata();
        $this->watchlist = new MockWatchList();
    }
}
