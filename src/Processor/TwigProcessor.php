<?php

namespace Couscous\Processor;

use Couscous\Page;
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
     * @var string
     */
    private $baseUrl;

    /**
     * @param Twig_Environment $twig
     * @param string           $baseUrl
     */
    public function __construct(Twig_Environment $twig, $baseUrl)
    {
        $this->twig = $twig;
        $this->baseUrl = $baseUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Page $page)
    {
        $context = (array) $page;
        $context['baseUrl'] = $this->baseUrl;

        $page->content = $this->twig->render($page->template . '.twig', $context);
    }
}
