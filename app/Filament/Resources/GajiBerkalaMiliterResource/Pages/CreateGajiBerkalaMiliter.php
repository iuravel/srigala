<?php

namespace App\Filament\Resources\GajiBerkalaMiliterResource\Pages;

use App\Filament\Resources\GajiBerkalaMiliterResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateGajiBerkalaMiliter extends CreateRecord
{
    protected static string $resource = GajiBerkalaMiliterResource::class;
    //protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Kembali')
                ->url(fn (): string => $this->getResource()::getUrl('index'))->hiddenLabel()->button()->icon('heroicon-s-arrow-uturn-left')->outlined()->color('gray'),
        ];
    }
    public function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Simpan')->color('primary'),
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()->label('Simpan & Buat lainnya')->color('primary')->outlined()] : []),
            $this->getCancelFormAction(),
        ];
    }
}