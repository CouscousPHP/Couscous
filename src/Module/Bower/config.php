<?php

return [

    'steps.preprocessing' => DI\add([
        DI\get('Couscous\Module\Bower\Step\RunBowerInstall'),
    ]),

];
