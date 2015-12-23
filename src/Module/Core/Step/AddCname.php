<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\Project;
use Couscous\Step;
use Couscous\Module\Template\Model\CnameFile;

/**
 * Adds the CNAME file to project
 *
 * @author Leonardo Ruhland <leoruhland@gmail.com>
 */

class AddCname implements Step
{

    public function __invoke(Project $project)
    {
        if (isset($project->metadata['cname'])) {
            $project->addFile(new CnameFile('CNAME', $project->metadata['cname']));
        }
    }
}
