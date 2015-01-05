<?php

return [

    'steps.preprocessing' => DI\add([
        DI\link('Couscous\Module\Bower\Step\RunBowerInstall'),
    ]),

];
