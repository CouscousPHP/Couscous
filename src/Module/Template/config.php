<?php

return [

    'steps.before' => DI\add([
        DI\get('Couscous\Module\Template\Step\UseDefaultTemplate'),
        DI\get('Couscous\Module\Template\Step\FetchRemoteTemplate'),
        DI\get('Couscous\Module\Template\Step\ValidateTemplateDirectory'),
    ]),
    'steps.postprocessing' => DI\add([
        DI\get('Couscous\Module\Template\Step\LoadAssets'),
        DI\get('Couscous\Module\Template\Step\AddPageListToLayoutVariables'),
        DI\get('Couscous\Module\Template\Step\ProcessTwigLayouts'),
    ]),

];
