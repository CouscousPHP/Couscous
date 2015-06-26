<?php

return [

    'steps.init' => DI\add([
        DI\get('Couscous\Module\Config\Step\SetDefaultConfig'),
        DI\get('Couscous\Module\Config\Step\LoadConfig'),
        DI\get('Couscous\Module\Config\Step\OverrideBaseUrlForPreview'),
    ]),

];
