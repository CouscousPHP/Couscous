<?php

return [

    'steps.after' => DI\add([
        DI\link('Couscous\Module\Core\Step\ClearTargetDirectory'),
        DI\link('Couscous\Module\Core\Step\WriteFiles'),
    ]),

];
