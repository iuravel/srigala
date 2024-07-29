<?php

namespace App\Filament\Resources\GajiBerkalaMiliterResource\Pages;

use App\Filament\Resources\GajiBerkalaMiliterResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditGajiBerkalaMiliter extends EditRecord
{
    protected static string $resource = GajiBerkalaMiliterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Kembali')
                ->url(fn (): string => $this->getResource()::getUrl('view', ['record' => $this->getRecord()]))->hiddenLabel()->button()->icon('heroicon-s-arrow-uturn-left')->outlined()->color('gray'),
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
    public function getFormActions(): array
    {
        return [
            parent::getSaveFormAction()->label('Perbarui')->icon('heroicon-o-pencil-square'),
            parent::getCancelFormAction()->label('Batal'),
            //Action::make('close')->action('saveAndClose'),
        ];
    }
}
