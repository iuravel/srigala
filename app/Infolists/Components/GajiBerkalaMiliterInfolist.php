<?php

namespace App\Infolists\Components;

use Filament\Forms\Components\Component;

class GajiBerkalaMiliterInfolist extends Component
{
    protected string $view = 'infolists.components.srigala.gaji-berkala-militer-infolist';

    public static function make(): static
    {
        return app(static::class);
    }
    
}
