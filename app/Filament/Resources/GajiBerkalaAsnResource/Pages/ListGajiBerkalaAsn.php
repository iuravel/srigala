<?php

namespace App\Filament\Resources\GajiBerkalaAsnResource\Pages;

use App\Exports\GajiBerkalaAsnExport;
use App\Filament\Resources\GajiBerkalaAsnResource;
use App\Imports\GajiBerkalaAsnImport;
use App\Imports\GapokAsnImport;
use App\Models\GajiBerkalaAsn;
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

class ListGajiBerkalaAsn extends ListRecords
{
    protected static string $resource = GajiBerkalaAsnResource::class;
    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat baru')->icon('heroicon-m-plus-circle'),
            //Import
            Action::make('importGajiBerkalaAsn')
                ->label('Impor')->outlined()->icon('heroicon-o-document-arrow-up')
                ->slideOver()
                ->form([ FileUpload::make('lampiran') ])
                ->action(function (array $data) {
                    DB::table('gaji_berkala_asn')->truncate();
                    $file = public_path('storage/'.$data['lampiran']);
                    Excel::import(new GajiBerkalaAsnImport, $file);
                    \Filament\Notifications\Notification::make()
                        ->title('Data berhasil diimpor.')
                        ->success()->send();
                }),
            
            //Export
            Action::make('exportGajiBerkalaAsn')
                ->label('Ekspor')->outlined()->icon('heroicon-o-document-arrow-down')
                ->form([ ToggleButtons::make('download') ])
                ->action(function (array $data) {
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Data berhasil diexport.')
                        ->success()->send();
                    return new GajiBerkalaAsnExport();
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
                ->badge(GajiBerkalaAsn::query()
                    ->whereYear('tmt_kgb_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_lama', '=', $bulanSekarang)->count())
                    ->icon('heroicon-s-check-badge')->badgeColor('success'),
            'Bulan Depan' => Tab::make('Bulan Depan')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $bulanDepan))
                ->badge(GajiBerkalaAsn::query()
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $bulanDepan)->count())
                    ->icon('heroicon-s-bell')->badgeColor('info'),
            '2 Bulan Kedepan' => Tab::make('2 Bulan Kedepan')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $duaBulanKedepan))
                ->badge(GajiBerkalaAsn::query()
                    ->whereYear('tmt_kgb_yad_lama', '=', $tahunSekarang)
                    ->whereMonth('tmt_kgb_yad_lama', '=', $duaBulanKedepan)->count())
                    ->icon('heroicon-s-bell')->badgeColor('info'),
            'Perlu Update' => Tab::make('Perlu Update')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('tmt_kgb_baru', '=', $tahunSekarang))
                ->badge(GajiBerkalaAsn::query()
                    ->whereYear('tmt_kgb_baru', '=', $tahunSekarang)->count())
                    ->icon('heroicon-m-bell-slash')->badgeColor('danger'),
        ];
    }
}
