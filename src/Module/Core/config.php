<?php

return [

    'steps.after' => DI\add([
        DI\link('Couscous\Module\Core\Step\AddImages'),
        DI\link('Couscous\Module\Core\Step\ClearTargetDirectory'),
        DI\link('Couscous\Module\Core\Step\WriteFiles'),
    ]),

];
