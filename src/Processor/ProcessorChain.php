<?php

namespace Couscous\Processor;

use Couscous\Model\Page;

/**
 * Chains processors together.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ProcessorChain implements Processor
{
    /**
     * @var Processor[]
     */
    private $processors = array();

    /**
     * Add a processor in the chain.
     *
     * @param Processor $processor
     *
     * @return $this
     */
    public function chain(Processor $processor)
    {
        $this->processors[] = $processor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Page $page)
    {
        foreach ($this->processors as $processor) {
            $processor->process($page);
        }
    }
}
