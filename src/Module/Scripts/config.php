<?php

return [

    'steps.before' => DI\add([
        DI\get('Couscous\Module\Scripts\Step\ExecuteBeforeScripts'),
    ]),
    'steps.after' => DI\add([
        DI\get('Couscous\Module\Scripts\Step\ExecuteAfterScripts'),
    ]),

];
