<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
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
