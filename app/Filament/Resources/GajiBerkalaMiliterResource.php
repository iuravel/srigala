<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GajiBerkalaMiliterResource\Pages;
use App\Models\BentukSurat;
use App\Models\GajiBerkalaMiliter;
use App\Models\GapokMiliter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Tables\Filters\Indicator;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Barryvdh\DomPDF\Facade\Pdf;

class GajiBerkalaMiliterResource extends Resource
{
    protected static ?string $model = GajiBerkalaMiliter::class;
    protected static ?string $slug = 'kgb-militer';
    protected static ?string $navigationGroup = 'Gaji Berkala';
    protected static ?string $modelLabel = 'KGB Militer';  
    protected static ?string $navigationLabel = 'KGB MILITER';
    protected static ?int $navigationSort = 1;
    //public bool $isTableReordering = true;
    

    public static function form(Form $form): Form
    {
       
        return $form
            ->schema([
//start schema
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    Grid::make(['default' => 1,'sm' => 2,'md' => 3,'lg' => 4,'xl' => 6,'2xl' => 8,]), //set default
                    Section::make('Data Personel')
                    ->columnSpanFull()
                    ->id('dataPersonel')
                    ->iconColor('info')
                    ->icon('heroicon-m-user')
                    ->schema([
                        Grid::make(6)->schema([
                            TextInput::make('nama')->prefix('NAMA')->hiddenLabel()
                            ->required()->maxLength(255)
                            ->columnSpan(2),
                            TextInput::make('nrp')->prefix('NRP')->hiddenLabel()
                            ->unique(column: 'nrp', ignoreRecord: true)->required()->mask('9999999999999999999999999')
                            ->validationAttribute('NRP')->validationMessages(['unique' => ':attribute sudah ada.',])
                            ->columnSpan(2),
                        ]),
                        Grid::make(6)->schema([
                            Radio::make('jenis_kelamin_id')->inline()->inlineLabel(true)->hiddenLabel()
                            ->required()
                            ->options(['1'=>'Laki-laki', '2'=>'Perempuan'])
                            ->columnSpan(2),
                            DatePicker::make('tanggal_lahir')->prefixIcon('heroicon-m-gift')->prefix('TGL LAHIR')
                            ->native(false)->displayFormat('d-m-Y')->inlineLabel()->hiddenLabel()
                            ->columnSpan(3),
                        ]),
                        Grid::make(6)->schema([
                            TextInput::make('jabatan')->hiddenLabel()->prefix('JABATAN')
                            ->maxLength(255)
                            ->columnSpan(3),
                            TextInput::make('kesatuan')->hiddenLabel()->prefix('KESATUAN')->default('Paldam III/Siliwangi')
                            ->maxLength(255)
                            ->columnSpan(3)
                        ]),
                        Grid::make(6)->schema([
                            DatePicker::make('tmt_tni')->hiddenLabel()->prefix('TMT TNI')->prefixIcon('heroicon-m-calendar')->displayFormat('d-m-Y')->native(false)
                            ->required()
                            ->columnSpan(2),
                            Select::make('korp_id')->hiddenLabel()->prefix('KORP')
                            ->relationship(name: 'korp', titleAttribute: 'nama')->native(false)->preload()
                            ->columnSpan(2)
                        ]),
                    ]),
                    
                    Section::make('Keadaan Lama')
                        ->columnSpanFull()
                        ->id('keadaanLama')
                        ->icon('heroicon-m-battery-50')
                        ->iconColor('warning')
                        ->collapsible()
                        //->collapsed()
                        ->schema (
                            [
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('tahun_mks_lama')->label('M K S')->mask('99')->suffix('Thn ')->numeric()->maxLength(100)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) {
                                        if ( empty($get('tahun_mkg_lama')) ) {
                                            $set('tahun_mkg_lama', $state) ?? null; }
                                    }),
                                    TextInput::make('bulan_mks_lama')->label(',')->mask('99')->suffix('Bln')->numeric()->maxLength(100)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) {
                                        if ( empty($get('bulan_mkg_lama')) ) {
                                            $set('bulan_mkg_lama', $state) ?? null; }
                                    }),
                                ])->columnSpan(2)->label('MKS'),
                                Cluster::make([
                                    TextInput::make('tahun_mkg_lama')->mask('99')->suffix('Thn')->numeric()->maxLength(100)->columnSpan(1)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) {
                                        self::updateByMkg($state, $get, $set);
                                    })
                                    ,
                                    TextInput::make('bulan_mkg_lama')->mask('99')->suffix('Bln')->numeric()->maxLength(100)->columnSpan(1),
                                ])->columnSpan(2)->label('MKG'),

                                ]),
                            Grid::make(6)->schema([
                                    Select::make('pangkat_lama_id')->hiddenLabel()->prefix('PANGKAT')
                                    ->relationship(name: 'pangkatLama', titleAttribute: 'nama', modifyQueryUsing:fn (Builder $query) => $query->orderBy('id'))->native(false)->columnSpan(2)
                                    ->live(onBlur:true)
                                    ->afterStateUpdated(function ($state, get $get, set $set) 
                                    {
                                        self::getKeadaanLama($state, $get, $set);
                                    })
                                    ->suffixAction(
                                        Action::make('charge')->label('Charge')
                                        ->icon('heroicon-o-arrows-up-down')->color('success')
                                        ->action(function ($state, get $get, set $set) {
                                            if ( (isset($state) && !empty($get('pangkat_lama_id'))) )
                                            {
                                                self::updateFieldKeadaanLama($state, $get, $set) ?? null;    
                                            }
                                        })
                                    ),
                                    TextInput::make('gaji_pokok_lama')->hiddenLabel()->prefix('GAJI POKOK')->mask('99999999999999999999')->numeric()->columnSpan(2)
                                    ->live()->afterStateUpdated(function ($state, get $get, set $set) { } ),
                                ]),
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('nomor_skep_lama')->prefix('NO. SKEP')->hiddenLabel()->maxLength(255),
                                    DatePicker::make('tmt_kgb_lama')->prefixIcon('heroicon-m-calendar')->prefix('TMT')->hiddenLabel()->native(false)->displayFormat('d-m-Y'),
                                ])->columnSpan(4)->hiddenLabel(),
                                    DatePicker::make('tmt_kgb_yad_lama')->prefixIcon('heroicon-o-bell')->prefix('YAD')->hiddenLabel()->native(false)->displayFormat('d-m-Y')->columnSpan(2),
                                ])
                            ]
                        ),

                    Section::make('Keadaan Baru')
                        ->columnSpanFull()
                        ->id('keadaanBaru')
                        ->iconColor('success')
                        ->icon('heroicon-m-battery-100')
                        ->collapsible()
                        //->collapsed()
                        ->schema([
                            Grid::make(6)->schema([
                                Select::make('pangkat_baru_id')->hiddenLabel()->prefix('PANGKAT')
                                ->relationship(name: 'pangkatBaru', titleAttribute: 'nama', modifyQueryUsing:fn (Builder $query) => $query->orderBy('id'))->native(false)->columnSpan(2)
                                ->disableOptionWhen(fn (string $value, get $get): bool => $value < $get('pangkat_lama_id'))
                                ->suffixAction(
                                    Action::make('charge')->label('Charge')
                                    ->icon('heroicon-o-arrows-up-down')->color('warning')
                                    ->action(function ($state, get $get, set $set) {
                                        if ( isset($state) && !empty($get('pangkat_lama_id')) && !empty($get('tahun_mks_lama')) ) 
                                        {
                                            self::getKeadaanBaru($state, $get, $set);
                                        }
                                    })
                                ),
                            ]),
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('tahun_mks_baru')->mask('99')->suffix('Thn')->numeric()->maxLength(100),
                                    TextInput::make('bulan_mks_baru')->mask('99')->suffix('Bln')->numeric()->maxLength(100),
                                ])->label('MKS')->columnSpan(2),
                                Cluster::make([
                                    TextInput::make('tahun_mkg_baru')->mask('99')->suffix('Thn')->numeric()->maxLength(100),
                                    TextInput::make('bulan_mkg_baru')->mask('99')->suffix('Bln')->numeric()->maxLength(100),
                                ])->label('MKG')->columnSpan(2),
                                TextInput::make('gaji_pokok_baru')->label('GAJI POKOK')->mask('9999999999999999999999999')->numeric()->columnSpan(2)
                            ]),
                            Grid::make(6)->schema([
                                Cluster::make([
                                    TextInput::make('nomor_skep_baru')->prefix('NO. SKEP')->hiddenLabel()->rules(['max:255']),
                                    DatePicker::make('tmt_kgb_baru')->prefixIcon('heroicon-m-calendar')->prefix('TMT')->hiddenLabel()->native(false)->displayFormat('d-m-Y'),
                                ])->columnSpan(4)->hiddenLabel(),
                                DatePicker::make('tmt_kgb_yad_baru')->prefixIcon('heroicon-o-bell-alert')->prefix('YAD')->hiddenLabel()->native(false)->displayFormat('d-m-Y')->columnSpan(2),
                            ]),
                            Grid::make(6)->schema([
                                Textarea::make('keterangan')->label('KETERANGAN')->rules(['max:255'])->columnSpan(4)
                            ])
                    ]),
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ]); //end schema
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('id', 'desc')
            ->actions([], position: ActionsPosition::BeforeColumns)
            ->deferLoading()
            ->columns([
                ColumnGroup::make('PERSONEL', [
                    //TextColumn::make('index')->rowIndex(),
                    TextColumn::make('nama')->label('NAMA')->searchable()->sortable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('nrp')->label('NRP')->searchable()->copyable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('jenisKelamin.singkatan')->label('JK')->sortable()->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('jabatan')->label('JABATAN')->searchable()->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('kesatuan')->label('KESATUAN')->searchable()->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('tanggal_lahir')->date('d-m-Y')->label('TGL LAHIR')->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('tmt_tni')->date('d-m-Y')->label('TMT TNI')->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                ])->alignment(Alignment::Center),
                ColumnGroup::make('KEADAAN LAMA', [
                    TextColumn::make('pangkat_lama_id')->label('Pangkat')
                        ->formatStateUsing(function ($record) {
                            $is_kowad = ($record->jenis_kelamin_id == 2) ? '(K)' : null;
                            $korp = (isset($record->korp_id)) ? ' '.$record->korp->nama : null;
                            return $record->pangkatLama->nama .' '.$is_kowad.''.$korp;
                        })->sortable()->searchable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('full_mks_lama')->label('MKS')->size(TextColumnSize::ExtraSmall)
                        ->formatStateUsing(fn ($record) => "{$record->tahun_mks_lama}".' Thn, '."{$record->tahun_mks_lama}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('full_mkg_lama')->label('MKG')->size(TextColumnSize::ExtraSmall)
                        ->formatStateUsing(fn ($record) => "{$record->tahun_mkg_lama}".' Thn, '."{$record->tahun_mkg_lama}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('gaji_pokok_lama')->label('GAJI POKOK')->size(TextColumnSize::ExtraSmall)
                        ->numeric(),
                    TextColumn::make('tmt_kgb_lama')->label('TMT KGB')->size(TextColumnSize::ExtraSmall)->color('success')
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                    TextColumn::make('tmt_kgb_yad_lama')->label('TMT YAD')->size(TextColumnSize::ExtraSmall)->color('info')
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                ])->alignment(Alignment::Center),
                
                ColumnGroup::make('KEADAAN BARU', [
                    TextColumn::make('pangkatBaru.nama')->label('PANGKAT')
                        ->formatStateUsing(function ($record) {
                            $is_kowad = ($record->jenis_kelamin_id == 2) ? '(K)' : null;
                            $korp = (isset($record->korp_id)) ? ' '.$record->korp->nama : null;
                            return $record->pangkatBaru->nama .' '.$is_kowad.''.$korp;
                        })
                        ->sortable()->searchable()->size(TextColumnSize::ExtraSmall),
                    TextColumn::make('full_mks_baru')->label('MKS')->size(TextColumnSize::ExtraSmall)
                        ->formatStateUsing(fn ($record) => "{$record->tahun_mks_baru}".' Thn, '."{$record->bulan_mks_baru}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('full_mkg_baru')->label('MKG')->size(TextColumnSize::ExtraSmall)
                            ->formatStateUsing(fn ($record) => "{$record->tahun_mkg_baru}".' Thn, '."{$record->bulan_mkg_baru}".' Bln')->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('gaji_pokok_baru')->label('GAJI POKOK')->size(TextColumnSize::ExtraSmall)
                        ->numeric(),
                    TextColumn::make('tmt_kgb_baru')->label('TMT KGB')->size(TextColumnSize::ExtraSmall)
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)->color('danger')
                        ->sortable(),
                    TextColumn::make('tmt_kgb_yad_baru')->label('TMT YAD')->size(TextColumnSize::ExtraSmall)
                        ->date('d-m-Y')->date('d-m-Y')->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                    TextColumn::make('keterangan')->label('KET')->size(TextColumnSize::ExtraSmall)->toggleable(isToggledHiddenByDefault: true),
                ])
                ->alignment(Alignment::Center),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Mulai Tanggal')->native(false)->displayFormat('d-m-Y'),
                        DatePicker::make('created_until')->label('Sampai Tanggal')->native(false)->displayFormat('d-m-Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tmt_kgb_lama', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tmt_kgb_lama', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Mulai dari: ' . Carbon::parse($data['created_from'])->isoFormat('D MMMM Y'))
                                ->removeField('from');
                        }
                 
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Sampai: ' . Carbon::parse($data['created_until'])->isoFormat('D MMMM Y'))
                                ->removeField('until');
                        }
                 
                        return $indicators;
                    }),
                    
                    Tables\Filters\TrashedFilter::make()->native(false)
            ])
            
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->size(ActionSize::Medium)->slideOver()
                        ->icon('heroicon-o-viewfinder-circle')->color('info'),
                    Tables\Actions\Action::make('Unduh')
                        ->action(function ($record) {
                            $currentRecord = $record;
                            $surat = BentukSurat::find(1);
                            $pdf = Pdf::loadView('pdf.kgb-militer-pdf', ['record' => $currentRecord, 'surat' => $surat]);
                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'kgb-'.strtolower(strval($record->nama)).'-'.strval($record->nrp).'.pdf');
                        })
                        ->icon('heroicon-m-arrow-down-tray')->size(ActionSize::Medium)->color('primary'),
                    Tables\Actions\EditAction::make()->size(ActionSize::Medium)->slideOver()
                        ->icon('heroicon-o-pencil-square')->color('warning'),
                    Tables\Actions\DeleteAction::make()->size(ActionSize::Medium)
                        ->icon('heroicon-o-archive-box-x-mark')->color('destroy'),
                ])->color('gray'),
                
                // TEST FORMAT
                Tables\Actions\Action::make('Cetak')
                    ->url(fn (GajiBerkalaMiliter $record) => route('kgb.militer.preview', $record))
                    ->iconButton()->icon('heroicon-o-printer')->size(ActionSize::ExtraSmall)->color('primary')
                    ->openUrlInNewTab(),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGajiBerkalaMiliter::route('/'),
            'create' => Pages\CreateGajiBerkalaMiliter::route('/create'),
            'view' => Pages\ViewGajiBerkalaMiliter::route('/{record}'),
            'edit' => Pages\EditGajiBerkalaMiliter::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
        
        
    //     return $infolist
    //         ->schema([
    //             Components\Grid::make(['default' => 2,'sm' => 2,'md' => 3,'lg' => 4,'xl' => 6,'2xl' => 8,]), //set default
    //             Components\Fieldset::make('DATA PERSONEL')
    //             ->columns(3)
    //             ->schema([
    //                 TextEntry::make('nama')->label('Nama')->weight('bold')->default('-')->color('gray'),
    //                 TextEntry::make('nrp')->label('NRP')->weight('bold')->default('-')->color('gray'),
    //                 TextEntry::make('tanggal_lahir')->label('Tanggal lahir')->weight('bold')->date('d-m-Y')->color('gray'),
    //                 TextEntry::make('jenisKelamin.nama')->label('JK')->weight('bold')->default('-')->color('gray'),
    //                 TextEntry::make('tmt_tni')->label('TMT TNI')->weight('bold')->date('d-m-Y')->color('gray'),
    //                 TextEntry::make('korp.nama')->label('KORP')->weight('bold')->default('-')->color('gray'),
    //                 TextEntry::make('jabatan')->label('Jabatan')->weight('bold')->default('-')->color('gray'),
    //                 TextEntry::make('kesatuan')->label('Kesatuan')->weight('bold')->default('-')->color('gray'),
    //             ]),
    //             Components\Fieldset::make('KEADAAN LAMA')
    //             ->columns(4)
    //             ->schema([
    //                 Components\Grid::make(4)->schema([
    //                     TextEntry::make('tahun_mks_lama')->label('MKS (Thn)')->weight('bold')->suffix(' Tahun')->default(0)->color('gray'),
    //                     TextEntry::make('bulan_mks_lama')->label('MKS (Bln)')->weight('bold')->suffix(' Bulan')->default(0)->color('gray'),
    //                     TextEntry::make('tahun_mkg_lama')->label('MKG (Thn)')->weight('bold')->suffix(' Tahun')->default(0)->color('gray'),
    //                     TextEntry::make('bulan_mkg_lama')->label('MKS (Bln)')->weight('bold')->suffix(' Bulan')->default(0)->color('gray'),
    //                     TextEntry::make('pangkatLama.nama')->label('Pangkat')->weight('bold')->default('-')->color('gray'),
    //                     TextEntry::make('gaji_pokok_lama')->label('Gaji Pokok')->weight('bold')->default(0)->columnSpan(3)->color('gray'),
    //                     TextEntry::make('nomor_skep_lama')->label('Nomor SKEP')->weight('bold')->default('-')->color('gray'),
    //                     TextEntry::make('tmt_kgb_lama')->label('TMT KGB')->weight('bold')->date('d-m-Y')->color('gray'),
    //                     TextEntry::make('tmt_kgb_yad_lama')->label('TMT KGB YAD')->weight('bold')->date('d-m-Y')->color('gray'),
    //                 ]),
    //             ]),
    //             Components\Fieldset::make('KEADAAN BARU')
    //             ->columns(4)
    //             ->schema([
    //                 Components\Grid::make(4)->schema([
    //                     TextEntry::make('tahun_mks_lama')->label('MKS (Thn)')->weight('bold')->suffix(' Tahun')->default(0)->color('gray'),
    //                     TextEntry::make('bulan_mks_lama')->label('MKS (Bln)')->weight('bold')->suffix(' Bulan')->default(0)->color('gray'),
    //                     TextEntry::make('tahun_mkg_lama')->label('MKG (Thn)')->weight('bold')->suffix(' Tahun')->default(0)->color('gray'),
    //                     TextEntry::make('bulan_mkg_lama')->label('MKS (Bln)')->weight('bold')->suffix(' Bulan')->default(0)->color('gray'),
    //                     TextEntry::make('pangkatLama.nama')->label('Pangkat')->weight('bold')->default('-')->color('gray')->color('gray'),
    //                     TextEntry::make('gaji_pokok_lama')->label('Gaji Pokok')->weight('bold')->default(0)->columnSpan(3)->color('gray'),
    //                     TextEntry::make('nomor_skep_lama')->label('Nomor SKEP')->weight('bold')->default('-')->color('gray'),
    //                     TextEntry::make('tmt_kgb_lama')->label('TMT KGB')->weight('bold')->date('d-m-Y')->color('gray'),
    //                     TextEntry::make('tmt_kgb_yad_lama')->label('TMT KGB YAD')->weight('bold')->date('d-m-Y')->color('gray'),
    //                 ]),
    //             ])
    //         ]);
    // }

    
    public static function getKeadaanLama($state, get $get, set $set) : void
    {
        $field = [ 'tmt_tni' => $get('tmt_tni') ];
        $old_field = [
            'pangkat' => $get('pangkat_lama_id') ?? null,
            'thn_mks' => $get('tahun_mks_lama') ?? null,
            'bln_mks' => $get('bln_mks_lama') ?? null,
            'thn_mkg' => $get('thn_mkg_lama') ?? null,
            'bln_mkg' => $get('bln_mkg_lama') ?? null,
            'gapok' => $get('gaji_pokok_lama') ?? null,
            'no_skep' => $get('no_skep_lama') ?? null,
            'tmt_kgb' => $get('tmt_kgb_lama') ?? null,
            'tmt_kgb_yad' => $get('tmt_kgb_yad_lama') ?? null
        ];

        $gapok = GapokMiliter::get();
        
        if (!empty($field['tmt_tni'] && !empty($old_field['thn_mks']))) {

            $query = $gapok->where('pangkat_id', $state)
                ->where('masa_kerja', $old_field['thn_mks'])
                ->first();
            $set('gaji_pokok_lama', $query->gaji ?? null);

            $set('tmt_kgb_lama', Carbon::parse($field['tmt_tni'])->addYears((int)$old_field['thn_mks'])) ?? null;
            $set('tmt_kgb_yad_lama', Carbon::parse($field['tmt_tni'])->addYears((int)$old_field['thn_mks'] + (int)2 )) ?? null;
            
            if (empty($get('pangkat_baru_id'))){
                $set('pangkat_baru_id', $old_field['pangkat']);
            }
        }
        
    }
    public static function updateFieldKeadaanLama($state, get $get, set $set) : void
    {
        $set('tahun_mks_lama', $get('tahun_mks_baru') ?? null);
        $set('bulan_mks_lama', $get('bulan_mks_baru') ?? null);
        $set('tahun_mkg_lama', $get('tahun_mkg_baru') ?? null);
        $set('bulan_mkg_lama', $get('bulan_mkg_baru') ?? null);
        $set($state, $get('pangkat_baru_id') ?? null);
        $set('gaji_pokok_lama', $get('gaji_pokok_baru') ?? null);
        $set('nomor_skep_lama', $get('nomor_skep_baru') ?? null);
        $set('tmt_kgb_lama', $get('tmt_kgb_baru') ?? null);
        $set('tmt_kgb_yad_lama', $get('tmt_kgb_yad_baru') ?? null);
    }
    public static function updateByMkg($state, get $get, set $set) : void
    {
        if (!empty($state)) 
        {
            $gapok = GapokMiliter::get();
            $query = $gapok->where('pangkat_id', $get('pangkat_lama_id'))
                ->where('masa_kerja', $state)
                ->first();

            $set('gaji_pokok_lama', $query->gaji ?? null);
        }
    }
    public static function getKeadaanBaru($state, get $get, set $set) : void
    {
        // $field = [ 'tmt_tni' => $get('tmt_tni') ];
        // $new_field = [
        //     'pangkat' => $get('pangkat_baru_id') ?? null,
        //     'thn_mks' => $get('tahun_mks_lama') ?? null,
        //     'bln_mks' => $get('bln_mks_lama') ?? null,
        //     'thn_mkg' => $get('thn_mkg_lama') ?? null,
        //     'bln_mkg' => $get('bln_mkg_lama') ?? null,
        //     'gapok' => $get('gaji_pokok_lama') ?? null,
        //     'no_skep' => $get('no_skep_lama') ?? null,
        //     'tmt_kgb' => $get('tmt_kgb_lama') ?? null,
        //     'tmt_kgb_yad' => $get('tmt_kgb_yad_lama') ?? null
        // ];

        if ( empty($get('tahun_mks_lama') && empty($get('tahun_mkg_lama'))) && empty($get('pangkat_lama_id')) && empty($get('tmt_kgb_lama')) && empty($get('tmt_kgb_yad_lama')) )
        {
            Notification::make()
            ->title('Keadaan Lama Tidak Valid.')
            ->warning()
            ->color('destroy')
            ->seconds(3)
            ->send();

            return;
        }

        $pangkat_lama = $get('pangkat_lama_id');
        $gapok = GapokMiliter::get();
        if ($state <= (int)6 || $pangkat_lama >= (int)7 && $state <= (int)12 || $pangkat_lama >= (int)13) 
        {
                $set('bulan_mks_baru', $get('bulan_mks_lama')) ?? null;
                $set('bulan_mkg_baru', $get('bulan_mkg_lama')) ?? null;
                $tmt_kgb_baru = $set('tmt_kgb_baru', $get('tmt_kgb_yad_lama')) ?? null;
                $set('tmt_kgb_yad_baru', Carbon::parse($tmt_kgb_baru)->addYears(2)) ?? null;
            //DEFAULT +2 TAHUN
            $mks_baru = $get('tahun_mks_lama') + (int)2;
            $mkg_baru = $get('tahun_mkg_lama') + (int)2;
            $set('tahun_mks_baru', $mks_baru);
            $set('tahun_mkg_baru', $mkg_baru);

            $query = $gapok->where('pangkat_id', $state)
                            ->where('masa_kerja', $mkg_baru)
                            ->first();
            $set('gaji_pokok_baru', $query->gaji ?? null);
        }
        elseif ($pangkat_lama <= (int)6 && $state == (int)7)
        {
                $set('bulan_mks_baru', $get('bulan_mks_lama')) ?? null;
                $set('bulan_mkg_baru', $get('bulan_mkg_lama')) ?? null;
                $tmt_kgb_baru = $set('tmt_kgb_baru', $get('tmt_kgb_yad_lama')) ?? null;
                $set('tmt_kgb_yad_baru', Carbon::parse($tmt_kgb_baru)->addYears(2)) ?? null;
            //TAMTAMA KE BINTARA SERDA MKG -4 Tahun
            $mks_baru = $get('tahun_mks_lama') - (int)4;
            $mkg_baru = $get('tahun_mkg_lama') - (int)4;
            $set('tahun_mks_baru', $mks_baru);
            $set('tahun_mkg_baru', $mkg_baru);

            $query = $gapok->where('pangkat_id', $state)
                            ->where('masa_kerja', $mkg_baru)
                            ->first();
            $set('gaji_pokok_baru', $query->gaji ?? null);
        }
        elseif ($pangkat_lama >= (int)7 && $state == (int)13)
        {
                $set('bulan_mks_baru', $get('bulan_mks_lama')) ?? null;
                $set('bulan_mkg_baru', $get('bulan_mkg_lama')) ?? null;
                $tmt_kgb_baru = $set('tmt_kgb_baru', $get('tmt_kgb_yad_lama')) ?? null;
                $set('tmt_kgb_yad_baru', Carbon::parse($tmt_kgb_baru)->addYears(2)) ?? null;
            //BINTARA KE PERWIRA - LETDA MKG -5 Tahun
            $mks_baru = $get('tahun_mks_lama') - (int)5;
            $mkg_baru = $get('tahun_mkg_lama') - (int)5;
            $set('tahun_mks_baru', $mks_baru);
            $set('tahun_mkg_baru', $mkg_baru);

            $query = $gapok->where('pangkat_id', $state)
                            ->where('masa_kerja', $mkg_baru)
                            ->first();
            $set('gaji_pokok_baru', $query->gaji ?? null);
        }
        
        

    } 
}
