<?php

namespace Couscous\Processor;

use Couscous\Page;

/**
 * Processes a page.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Processor
{
    /**
     * Processes the given page.
     *
     * @param Page $page
     */
    public function process(Page $page);
}
