<?php

namespace App\Filament\Resources\BentukSuratResource\Pages;

use App\Filament\Resources\BentukSuratResource;
use App\Models\BentukSurat;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBentukSurat extends EditRecord
{
    protected static string $resource = BentukSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(fn (): string => $this->getResource()::getUrl('index', ['record' => $this->getRecord()]))->hiddenLabel()->button()->icon('heroicon-s-arrow-uturn-left')->outlined()->color('gray'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        //return $this->getResource()::getUrl('index');
        //return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
        return false;
    }
}
