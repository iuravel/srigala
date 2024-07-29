<?php

namespace App\Filament\Resources\GapokMiliterResource\Pages;

use App\Filament\Resources\GapokMiliterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGapokMiliter extends EditRecord
{
    protected static string $resource = GapokMiliterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    // public function getFormActions(): array
    // {
    //     return [
    //         parent::getSaveFormAction()->label('Perbarui')->icon('heroicon-o-pencil-square'),
    //         parent::getCancelFormAction()->label('Batal'),
    //         //Action::make('close')->action('saveAndClose'),
    //     ];
    // }
    
}
