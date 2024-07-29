<?php

namespace App\Filament\Resources\BentukSuratResource\Pages;

use App\Filament\Resources\BentukSuratResource;
use Filament\Actions;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Pages\CreateRecord;

class CreateBentukSurat extends CreateRecord
{
    protected static string $resource = BentukSuratResource::class;

    public function getFormActions(): array
    {
        return [];
    }
}
