<?php

namespace Couscous\Processor;

use Couscous\Model\Page;
use Twig_Environment;

/**
 * Renders a template using Twig.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TwigProcessor implements Processor
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Page $page)
    {
        $context = (array) $page;

        $page->content = $this->twig->render($page->template . '.twig', $context);
    }
}
