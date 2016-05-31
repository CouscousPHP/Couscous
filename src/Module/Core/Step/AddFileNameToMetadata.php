<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\Project;
use Couscous\Step;

/**
 * Add to the current file name to the metadata.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AddFileNameToMetadata implements Step
{
    public function __invoke(Project $project)
    {
        foreach ($project->getFiles() as $file) {
            $fileMetadata = $file->getMetadata();
            $fileMetadata['currentFile'] = $file->relativeFilename;
        }
    }
}
