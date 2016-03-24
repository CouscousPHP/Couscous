<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\Project;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Step;

/**
 * Add Livereload snippet in html generated files.
 *
 * @author Gaultier Boniface <gboniface@wysow.fr>
 */
class AddLivereloadSnippet implements Step
{
    const LIVERELOAD_SNIPPET = '
        <!-- Livereload -->
        <script>
            document.write(\'<script src="http://\' + (location.host || \'localhost\').split(\':\')[0] +
                    \':35729/livereload.js?snipver=1"></\' + \'script>\')
        </script>
        <!-- End Livereload -->
    ';

    public function __invoke(Project $project)
    {
        if (!$project->metadata['preview']) {
            return;
        }

        /** @var HtmlFile[] $htmlFiles */
        $htmlFiles = $project->findFilesByType('Couscous\Module\Template\Model\HtmlFile');

        foreach ($htmlFiles as $file) {
            $contentAsArray = explode('</body>', $file->content);

            $contentAsArray[0] = trim($contentAsArray[0]).self::LIVERELOAD_SNIPPET;

            $file->content = implode('</body>', $contentAsArray);
        }
    }
}
