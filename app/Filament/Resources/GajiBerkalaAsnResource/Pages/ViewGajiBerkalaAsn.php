<?php

namespace App\Filament\Resources\GajiBerkalaAsnResource\Pages;

use App\Filament\Resources\GajiBerkalaAsnResource;
use App\Models\BentukSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewGajiBerkalaAsn extends ViewRecord
{
    protected static string $resource = GajiBerkalaAsnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make('Hapus')->hiddenLabel()->button()->icon('heroicon-o-archive-box-x-mark')->outlined()->color('destroy'),
            Action::make('print')
                ->label('Unduh')
                ->action('print')
                ->icon('heroicon-m-arrow-down-tray')->outlined(),
            
            Actions\EditAction::make('Ubah')->icon('heroicon-o-pencil-square')->color('warning'),
            Action::make('Kembali')
                ->url(fn (): string => $this->getResource()::getUrl('index'))->hiddenLabel()->button()->icon('heroicon-s-arrow-uturn-left')->outlined()->color('gray'),
        ];
    }
    public function print()
    {
        $record = $this->record; // Get the current record
        $surat = BentukSurat::findOrFail(1);
        //$pdf = PDF::loadView('pdf.kgb-militer-pdf', ['record' => $record]);
        $pdf = PDF::loadView('pdf.kgb-asn-pdf',  compact('record', 'surat'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'kgb-'.strtolower(strval($record->nama)).'-'.strval($record->nip).'.pdf');
    }
}
