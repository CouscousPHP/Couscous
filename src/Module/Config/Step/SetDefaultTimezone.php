<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Project;
use Couscous\Step;

/**
 * Set the default timezone if one is missing, to prevent PHP warnings.
 *
 * @author Max Podolian <max.podolian@gmail.com>
 */
class SetDefaultTimezone implements Step
{
    const DEFAULT_TIMEZONE = 'UTC';

    public function __invoke(Project $project)
    {
        if ($this->timezoneNotSet()) {
            date_default_timezone_set(self::DEFAULT_TIMEZONE);
        }
    }

    private function timezoneNotSet()
    {
        return ! ini_get('date.timezone');
    }


}
