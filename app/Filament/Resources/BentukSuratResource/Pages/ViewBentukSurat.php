<?php

namespace App\Filament\Resources\BentukSuratResource\Pages;

use App\Filament\Resources\BentukSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBentukSurat extends ViewRecord
{
    protected static string $resource = BentukSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    
}
