<?php

namespace Vanguard\Services\Design;

use Vanguard\Models\DesignItems;
use Vanguard\Services\ModelService;

class DesignMetaService extends ModelService
{

    public function __construct()
    {
        $this->model = resolve(DesignItems::class);
    }

    
    
}
