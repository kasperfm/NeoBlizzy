<?php

namespace KasperFM\NeoBlizzy;

use Illuminate\Support\Facades\Facade;

class NeoBlizzyFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'NeoBlizzy';
    }
}
