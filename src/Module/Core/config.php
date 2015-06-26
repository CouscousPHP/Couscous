<?php

return [

    'steps.after' => DI\add([
        DI\get('Couscous\Module\Core\Step\ClearTargetDirectory'),
        DI\get('Couscous\Module\Core\Step\WriteFiles'),
    ]),

];
