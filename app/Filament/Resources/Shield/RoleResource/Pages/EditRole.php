<?php

namespace App\Filament\Resources\Shield\RoleResource\Pages;

use App\Filament\Resources\Shield\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function getActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
            Actions\Action::make('Kembali')
                ->url(fn (): string => $this->getResource()::getUrl('index'))->hiddenLabel()->button()->icon('heroicon-s-arrow-uturn-left')->outlined()->color('gray'),
        ];
    }
    public function getFormActions(): array
    {
        return [
            parent::getSaveFormAction()->label('Perbarui')->icon('heroicon-o-pencil-square'),
            parent::getCancelFormAction()->label('Selesai'),
            //Action::make('close')->action('saveAndClose'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return ! in_array($key, ['name', 'guard_name', 'select_all']);
            })
            ->values()
            ->flatten()
            ->unique();

        return Arr::only($data, ['name', 'guard_name']);
    }

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }
}
