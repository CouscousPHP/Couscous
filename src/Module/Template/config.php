<?php

return [

    'steps.before' => DI\add([
        DI\link('Couscous\Module\Template\Step\UseDefaultTemplate'),
        DI\link('Couscous\Module\Template\Step\FetchRemoteTemplate'),
        DI\link('Couscous\Module\Template\Step\ValidateTemplateDirectory'),
    ]),
    'steps.postprocessing' => DI\add([
        DI\link('Couscous\Module\Template\Step\LoadAssets'),
        DI\link('Couscous\Module\Template\Step\AddPageListToLayoutVariables'),
        DI\link('Couscous\Module\Template\Step\ProcessTwigLayouts'),
    ]),

];
