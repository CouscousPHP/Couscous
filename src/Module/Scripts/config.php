<?php

return [

    'steps.before' => DI\add([
        DI\link('Couscous\Module\Scripts\Step\ExecuteBeforeScripts'),
    ]),
    'steps.after' => DI\add([
        DI\link('Couscous\Module\Scripts\Step\ExecuteAfterScripts'),
    ]),

];
