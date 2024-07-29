<?php

namespace App\Filament\Resources\GajiBerkalaMiliterResource\Pages;

use App\Exports\GajiBerkalaMiliterExport;
use App\Filament\Resources\GajiBerkalaMiliterResource;
use App\Imports\GajiBerkalaMiliterImport;
use App\Models\GajiBerkalaMiliter;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ListGajiBerkalaMiliter extends ListRecords
{
    protected static string $resource = GajiBerkalaMiliterResource::class;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat baru')->icon('heroicon-m-plus-circle'),

            //Import
            Action::make('importGajiBerkalaMiliter')
                ->label('Impor')->outlined()->icon('heroicon-o-document-arrow-up')
                ->slideOver()
                ->form([ FileUpload::make('lampiran') ])
                ->action(function (array $data) {
                    DB::table('gaji_berkala_militer')->truncate();
                    $file = public_path('storage/'.$data['lampiran']);
                    Excel::import(new GajiBerkalaMiliterImport, $file);
                    \Filament\Notifications\Notification::make()
                        ->title('Data berhasil diimpor.')
                        ->success()->send();
                }),
            
            //Export
            Action::make('exportGajiBerkalaMiliter')
                ->label('Ekspor')->outlined()->icon('heroicon-o-document-arrow-down')
                ->form([ ToggleButtons::make('download') ])
                ->action(function (array $data) {
                    \Filament\Notifications\Notification::make()
                        ->title('Data berhasil diexport.')
                        ->success()->send();
                    return new GajiBerkalaMiliterExport();
                })->modalHidden(),
            ];
    }
    public function getTabs(): array
    {
        $bulanSekarang = Carbon::now()->startOfMonth();
        $bulanDepan = Carbon::now()->addMonth()->startOfMonth();
        $duaBulanKedepan = Carbon::now()->addMonths(2)->startOfMonth();
        // $satuBulanLalu = Carbon::now()->subMonths(1)->startOfMonth();
        // $duaBulanLalu = Carbon::now()->subMonths(2)->month;
        $tahunSekarang = Carbon::now()->year;
        return [
            'Semua' => Tab::make('Semua')->badge(static::getModel()::count()),
            'Bulan Ini' => Tab::make('Bulan Ini')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('tmt_kgb_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_lama', '=', $bulanSekarang))
                ->badge(GajiBerkalaMiliter::query()
                    ->whereYear('tmt_kgb_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_lama', '=', $bulanSekarang)->count())
                    ->icon('heroicon-s-check-badge')->badgeColor('success'),
            'Bulan Depan' => Tab::make('Bulan Depan')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $bulanDepan))
                ->badge(GajiBerkalaMiliter::query()
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $bulanDepan)->count())
                    ->icon('heroicon-s-bell')->badgeColor('info'),
            '2 Bulan Kedepan' => Tab::make('2 Bulan Kedepan')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $duaBulanKedepan))
                ->badge(GajiBerkalaMiliter::query()
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $duaBulanKedepan)->count())
                    ->icon('heroicon-s-bell')->badgeColor('info'),
            'Perlu Update' => Tab::make('Perlu Update')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('tmt_kgb_baru', '=', $tahunSekarang))
                ->badge(GajiBerkalaMiliter::query()
                    ->whereYear('tmt_kgb_baru', '=', $tahunSekarang)->count())
                    ->icon('heroicon-m-bell-slash')->badgeColor('danger'),
        ];
    }
}
