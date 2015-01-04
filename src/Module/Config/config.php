<?php

return [

    'steps.init' => DI\add([
        DI\link('Couscous\Module\Config\Step\SetDefaultConfig'),
        DI\link('Couscous\Module\Config\Step\LoadConfig'),
        DI\link('Couscous\Module\Config\Step\OverrideBaseUrlForPreview'),
    ]),

];
