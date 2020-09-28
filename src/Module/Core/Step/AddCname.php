<?php
declare(strict_types = 1);

namespace Couscous\Module\Core\Step;

use Couscous\Model\Project;
use Couscous\Module\Template\Model\CnameFile;
use Couscous\Step;

/**
 * Add the CNAME file to project.
 *
 * @author Leonardo Ruhland <leoruhland@gmail.com>
 */
class AddCname implements Step
{
    public function __invoke(Project $project): void
    {
        if (isset($project->metadata['cname'])) {
            $project->addFile(new CnameFile('CNAME', (string) $project->metadata['cname']));
        }
    }
}
