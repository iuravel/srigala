<?php

namespace App\Filament\Resources\BentukSuratResource\Pages;

use App\Filament\Resources\BentukSuratResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListBentukSurat extends ListRecords
{
    protected static string $resource = BentukSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
    
}
